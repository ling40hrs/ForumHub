.PHONY: db

db:
	mysql -u root < database/schema.sql
