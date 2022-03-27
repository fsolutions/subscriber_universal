import os
import mysql.connector

async def find_new_channels(last_channel_subscribtion_id = 0):
  dbConn = mysql.connector.connect(user=os.getenv('DB_USERNAME'), 
                                  password=os.getenv('DB_PASSWORD'), 
                                  host=os.getenv('DB_HOST'), 
                                  database=os.getenv('DB_DATABASE'))
  cursor = dbConn.cursor()

  query = ("SELECT id, tg_channel_id, tg_channel_name, tg_channel_last_message_id FROM tg_bot_channel_subscribtions "
          "WHERE id > %s")

  cursor.execute(query, [last_channel_subscribtion_id])
  finalArrayOfChannels = []

  for (id, tg_channel_id, tg_channel_name, tg_channel_last_message_id) in cursor:
    # print("{}, {}, {}, {}".format(
    #   id, tg_channel_id, tg_channel_name, tg_channel_last_message_id))
    finalArrayOfChannels.append({'tg_channel_name': tg_channel_name, 'tg_channel_last_message_id': tg_channel_last_message_id})
    last_channel_subscribtion_id = id

  result = {
    'last_channel_subscribtion_id': last_channel_subscribtion_id,
    'all_channels': finalArrayOfChannels
  }

  cursor.close()
  dbConn.close()
  
  return result