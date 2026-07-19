up-%:
	./_scripts/up.sh $*

down-%:
	./_scripts/down.sh $*

build-%:
	./_scripts/build.sh $*

setup-%:
	./_scripts/setup.sh $*

worker-%:
	./_scripts/worker.sh $*

build-front:
	php bin/console cache:clear
	php bin/console tailwind:build
	php bin/console asset-map compile
bash-%:
	./_scripts/bash.sh $*

clear-%:
	./_scripts/clear.sh $*
