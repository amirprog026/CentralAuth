from peewee import MySQLDatabase


DATABASE = {
    'name': 'auth_service_db',
    'user': 'root',
    'password': 'yourpassword',
    'host': 'localhost',
    'port': 3306
}

# Initialize the database connection
db = MySQLDatabase(DATABASE['name'], 
                   user=DATABASE['user'], 
                   password=DATABASE['password'],
                   host=DATABASE['host'], 
                   port=DATABASE['port'])
