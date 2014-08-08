SELENIUM_SERVER_URL=http://selenium-release.storage.googleapis.com/2.42/selenium-server-standalone-2.42.2.jar
SELENIUM_SERVER=selenium-server.jar
PHPUNIT=vendor/bin/phpunit

clean:
	-rm ${SELENIUM_SERVER}

selenium-server:
	@echo "--- Checking if selenium server is present"
	test -f ${SELENIUM_SERVER} || wget ${SELENIUM_SERVER_URL} -O ${SELENIUM_SERVER}

test-function: selenium-server
	@echo "--- Running selenium server"
	java  -jar ${SELENIUM_SERVER} > /dev/null &
	@sleep 2
	@echo "--- Executing functional test suite"
	-${PHPUNIT} --testsuite functional
	@echo "--- Shutting down selenium server"
	kill `ps -ef | grep ${SELENIUM_SERVER} | grep -v grep | awk '{print $$2}'`

