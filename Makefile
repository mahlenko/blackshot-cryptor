frontend-install:
	./vendor/bin/sail npm install

frontend-watch:
	./vendor/bin/sail npm run watch

frontend-dev:
	./vendor/bin/sail npm run dev

frontend-prod:
	./vendor/bin/sail npm run prod

admin-watch:
	./vendor/bin/sail npm run admin-watch

admin-prod:
	./vendor/bin/sail npm run admin-production
