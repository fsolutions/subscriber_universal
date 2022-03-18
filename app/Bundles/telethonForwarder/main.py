import os
from dotenv import load_dotenv
from telethon import TelegramClient, functions, types, sync, events
# from telethon.tl.functions.channels import JoinChannelRequest

load_dotenv()

INPUT_CHANNEL = 'trychannel_fomichevms'
OUTPUT_CHANNEL = '@SubscriberUniversalBot'

client = TelegramClient('subscriber_user', os.getenv('TELETHON_API_ID'), os.getenv('TELETHON_API_HASH'))

with client:
    # NEED TEST OF EXIST OF SUBSCRIBTION
    result = client(functions.channels.JoinChannelRequest(
        channel=INPUT_CHANNEL
    ))
    print(result.stringify())

@client.on(events.NewMessage(chats=(INPUT_CHANNEL)))
async def normal_handler(event):
    # Send message to bot
    await client.forward_messages(OUTPUT_CHANNEL, event.message.id, event.message.peer_id.channel_id)   
    print(event.stringify())

client.start()
client.run_until_disconnected()