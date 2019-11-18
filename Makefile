
fix: ## phpcs
	php vendor/friendsofphp/php-cs-fixer/php-cs-fixer fix src/


analyse: ## analyse code with php stan
	php vendor/bin/phpstan analyse --level max src/


.PHONY: composer encore-dev encore-prod encore-watch nginx php yarn watch

.DEFAULT_GOAL := help
help:
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) \
		| awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' \
		| sed -e 's/\[32m##/[33m/'
.PHONY: help
