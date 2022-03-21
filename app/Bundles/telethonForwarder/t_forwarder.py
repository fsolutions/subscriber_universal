import sys 
import os
import asyncio
from dotenv import load_dotenv
from telethon import TelegramClient, functions, types, sync, events
import dbChannelsImport

load_dotenv()

output_channel = '@SubscriberUniversalBot'  # where to publish materials
last_channel_subscribtion_id = 0

async def main(loop, interval=2):
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
            # We want to update the application but get back
            # to asyncio's event loop. For this we sleep a
            # short time so the event loop can run.
            #
            # https://www.reddit.com/r/Python/comments/33ecpl
            await subscribtionLoop(client, output_channel)
            await asyncio.sleep(interval)
    except KeyboardInterrupt:
        pass
    # except tkinter.TclError as e:
    #     if 'application has been destroyed' not in e.args[0]:
    #         raise
    # finally:
        # await client.disconnect() 

async def test(incoming):
    return await incoming

async def subscribtionLoop(client, output_channel):
    global last_channel_subscribtion_id
    dbResult = await dbChannelsImport.findNewChannels(last_channel_subscribtion_id)
    last_channel_subscribtion_id = dbResult['last_channel_subscribtion_id']
    
    async with client:
        for tg_channel_name in dbResult['all_channels']:
            result = await client(functions.channels.JoinChannelRequest(
                channel=tg_channel_name
            ))
            # print(result.stringify())

            @client.on(events.NewMessage(chats=(tg_channel_name)))
            async def normal_handler(event):
                # Send message to bot
                await client.forward_messages(output_channel, event.message.id, event.message.peer_id.channel_id)   
                print(event.stringify())
                
    return True

asyncio.run(main(1))