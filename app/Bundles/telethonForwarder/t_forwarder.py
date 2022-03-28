#!/usr/bin/python

import psutil, setproctitle, sys, os, asyncio
from dotenv import load_dotenv
from telethon import TelegramClient, functions, types, sync, events
import dbChannelsImport
import dbChannelsLastMessage

load_dotenv()

setproctitle.setproctitle('t_forwarder.py')
output_channel = '@SubscriberUniversalBot'  # where to publish materials
last_channel_subscribtion_id = 0

def checkNumberOfProcessRunning(processName):
    procCount = 0
    #Iterate over the all the running process
    for proc in psutil.process_iter():
        try:
            # Check if process name contains the given name string.
            if processName.lower() in proc.name().lower():
                procCount = procCount + 1
        except (psutil.NoSuchProcess, psutil.AccessDenied, psutil.ZombieProcess):
            pass
    return procCount

async def main(loop, interval=3):
    client = TelegramClient('subscriber_user', 
            os.getenv('TELETHON_API_ID'), 
            os.getenv('TELETHON_API_HASH'), 
            loop = loop)
    try:
        await client.connect()
    except Exception as e:
        print('Failed to connect', e, file = sys.stderr)
        return

    try:
        while True:
            await subscribtion_loop(client, output_channel)
            await asyncio.sleep(interval)
    except KeyboardInterrupt:
        pass
    # except tkinter.TclError as e:
    #     if 'application has been destroyed' not in e.args[0]:
    #         raise
    # finally:
        # await client.disconnect() 

async def check_missed_messages(channel_id, last_message_id, new_message_id):
    await dbChannelsLastMessage.save_last_channels_messageId(channel_id, new_message_id)

    if last_message_id is None:
        last_message_id = new_message_id
        
    messagesForSend = list(range(last_message_id, new_message_id))
    messagesForSend.append(new_message_id)
    
    return messagesForSend

async def subscribtion_loop(client, output_channel):
    global last_channel_subscribtion_id
    try:
        dbResult = await dbChannelsImport.find_new_channels(last_channel_subscribtion_id)
        last_channel_subscribtion_id = dbResult['last_channel_subscribtion_id']
    except Exception as e:
        dbResult = {
            'last_channel_subscribtion_id': last_channel_subscribtion_id,
            'all_channels': []
        }
        print('Failed to load data from DB', e, file = sys.stderr)
    
    async with client:
        for tg_channel in dbResult['all_channels']:
            try:
                print(tg_channel['tg_channel_name'], tg_channel['tg_channel_last_message_id'])
                result = await client(functions.channels.JoinChannelRequest(
                    channel=tg_channel['tg_channel_name']
                ))
                # print(result.stringify())
            except Exception as e:
                print('Failed to join channel @' + tg_channel['tg_channel_name'], e, file = sys.stderr)

            try:
                @client.on(events.NewMessage(chats=(tg_channel['tg_channel_name'])))
                async def normal_handler(event):
                    messagesForSend = await check_missed_messages(event.message.peer_id.channel_id, tg_channel['tg_channel_last_message_id'], event.message.id)
                    tg_channel['tg_channel_last_message_id'] = event.message.id
                    # Send message to bot
                    # await client.forward_messages(output_channel, messagesForSend, event.message.peer_id.channel_id)   
                    await client.forward_messages(output_channel, event.message.id, event.message.peer_id.channel_id)   

                    print(event.stringify())
            except Exception as e:
                print('Failed to subscribe on NewMessage event for channel @' + tg_channel['tg_channel_name'], e, file = sys.stderr)
                
    return True

if checkNumberOfProcessRunning('t_forwarder.py') == 1:
    asyncio.run(main(1))
