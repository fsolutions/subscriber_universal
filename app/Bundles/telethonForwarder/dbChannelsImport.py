import mysql.connector

async def findNewChannels(last_channel_subscribtion_id = 0):
  cnx = mysql.connector.connect(user='subscriber', password='dZ3jX1tD0bdF7j', host='127.0.0.1', database='subscriber')
  cursor = cnx.cursor()

  query = ("SELECT id, tg_channel_id, tg_channel_name FROM tg_bot_channel_subscribtions "
          "WHERE id > %s")

  cursor.execute(query, [last_channel_subscribtion_id])
  finalArrayOfChannels = []

  for (id, tg_channel_id, tg_channel_name) in cursor:
    print("{}, {}, {}".format(
      id, tg_channel_id, tg_channel_name))
    # finalArrayOfChannels.append({id, tg_channel_id, tg_channel_name})
    finalArrayOfChannels.append(tg_channel_id)
    last_channel_subscribtion_id = id

  result = {
    'last_channel_subscribtion_id': last_channel_subscribtion_id,
    'all_channels': finalArrayOfChannels
  }

  cursor.close()
  cnx.close()
  
  return result