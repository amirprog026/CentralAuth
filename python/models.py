from peewee import Model, CharField, ForeignKeyField, TextField
from config import db

# User model
class User(Model):
    platform = CharField(max_length=255)
    username = CharField(unique=True, max_length=255)
    password = CharField(max_length=255)  # Store hashed password

    class Meta:
        database = db

# UserMeta model for storing additional user metadata
class UserMeta(Model):
    user = ForeignKeyField(User, backref='meta')
    key = CharField(max_length=255)
    value = TextField()

    class Meta:
        database = db

# Create tables if they don't exist
db.connect()
db.create_tables([User, UserMeta])
