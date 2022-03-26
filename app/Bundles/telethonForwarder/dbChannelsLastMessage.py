import sys
import os
import mysql.connector

async def save_last_channels_messageId(tg_channel_id, message_id):
  try:
    dbConn = mysql.connector.connect(user=os.getenv('DB_USERNAME'), 
                                     password=os.getenv('DB_PASSWORD'), 
                                     host=os.getenv('DB_HOST'), 
                                     database=os.getenv('DB_DATABASE'))
    cursor = dbConn.cursor()

    query = ("UPDATE tg_bot_channel_subscribtions SET tg_channel_last_message_id = %s "
            "WHERE tg_channel_id = -100%s")

    cursor.execute(query, [message_id, tg_channel_id])
    dbConn.commit()
    cursor.close()
    dbConn.close()
    
    return True
  except Exception as e:
    print('Failed to connect to DB', e, file = sys.stderr)
    return False
  
