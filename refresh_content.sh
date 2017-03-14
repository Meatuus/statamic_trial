trash site/content
trash site/storage
trash site/users

rsync -chavzP --stats serverpilot@138.197.136.63:/srv/users/serverpilot/apps/statamic-boilerplate/site/content ./site
rsync -chavzP --stats serverpilot@138.197.136.63:/srv/users/serverpilot/apps/statamic-boilerplate/site/storage ./site
rsync -chavzP --stats serverpilot@138.197.136.63:/srv/users/serverpilot/apps/statamic-boilerplate/site/users ./site
