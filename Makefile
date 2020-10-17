.PHONY: all_dev all_prod composer composer_prod apache_folders npm dev prod

all_dev: composer apache_folders npm dev

all_prod: composer_prod apache_folders npm prod

composer:
	@composer install
	@echo "composer installed"

composer_prod:
	@composer install --no-dev
	@echo "composer installed"

apache_folders:
	@sudo chgrp -R www-data storage bootstrap/cache
	@sudo chmod -R 775 storage bootstrap/cache
	@echo "Done making apache folders"

npm:
	@npm install
	@echo "npm installed"

dev:
	@npm run dev
	@echo "Done generating dev assets"

prod:
	@npm run prod
	@echo "done generating prod assets"


