all: build

build:
	jekyll build

deploy:
	jekyll serve

check:
	bundle exec jekyll build
