import sys 
import os
import asyncio
from dotenv import load_dotenv
from telethon import TelegramClient, functions, types, sync, events
import dbChannelsImport

load_dotenv()

output_channel = '@SubscriberUniversalBot'  # where to publish materials
last_channel_subscribtion_id = 0

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
            await subscribtionLoop(client, output_channel)
            await asyncio.sleep(interval)
    except KeyboardInterrupt:
        pass
    # except tkinter.TclError as e:
    #     if 'application has been destroyed' not in e.args[0]:
    #         raise
    # finally:
        # await client.disconnect() 

async def subscribtionLoop(client, output_channel):
    global last_channel_subscribtion_id
    try:
        dbResult = await dbChannelsImport.findNewChannels(last_channel_subscribtion_id)
        last_channel_subscribtion_id = dbResult['last_channel_subscribtion_id']
    except Exception as e:
        print('Failed to load data from DB', e, file = sys.stderr)
        dbResult['all_channels'] = {
            'last_channel_subscribtion_id': last_channel_subscribtion_id,
            'all_channels': []
        }
    
    async with client:
        for tg_channel_name in dbResult['all_channels']:
            try:
                result = await client(functions.channels.JoinChannelRequest(
                    channel=tg_channel_name
                ))
                # print(result.stringify())
            except Exception as e:
                print('Failed to join channel @' + tg_channel_name, e, file = sys.stderr)

            try:
                @client.on(events.NewMessage(chats=(tg_channel_name)))
                async def normal_handler(event):
                    # Send message to bot
                    await client.forward_messages(output_channel, event.message.id, event.message.peer_id.channel_id)   
                    print(event.stringify())
            except Exception as e:
                print('Failed to subscribe on NewMessage event for channel @' + tg_channel_name, e, file = sys.stderr)
                
    return True

asyncio.run(main(1))