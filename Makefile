all: build

build:
	jekyll build

debug:
	jekyll build --trace

deploy:
	jekyll serve

check:
	bundle exec jekyll build
