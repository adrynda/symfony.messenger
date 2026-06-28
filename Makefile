build-%:
	./_scripts/build.sh $*

setup-%:
	./_scripts/setup.sh $*

build-front:
	php bin/console cache:clear
	php bin/console tailwind:build
	php bin/console asset-map compile
