.PHONY: build run test shell

build:
	docker build -t cli-dungeon .

run:
	docker run --rm -it cli-dungeon

test:
	docker run --rm -it -v $(PWD):/app cli-dungeon vendor/bin/phpunit -c phpunit.xml.dist

shell:
	docker run --rm -it -v $(PWD):/app --entrypoint /bin/sh cli-dungeon
