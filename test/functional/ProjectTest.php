<?php

class EndToEndTest extends \PHPUnit_Extensions_SeleniumTestCase
{
    const TIMEOUT = 10000;
    const BASE_URL = "http://www.192.168.42.03.xip.io";

    const LOGIN = 'nemex';
    const PASSWORD = 'io';

    const ROUTE_LOGIN = '/login.php';
    const ROUTE_INDEX = '/index.php';

    private $projectName = '';
    private $baseUrl = 'http://www.192.168.42.03.xip.io';

    protected function setUp()
    {
        $this->cleanProjects();
        $this->configureProjectCredentials();

        $configureBaseUrl = getenv('NEMEX_URL');
        if ($configureBaseUrl) {
            $this->baseUrl = $configureBaseUrl;
        }

        //$this->setSpeed(500);
        $this->setBrowser("*chrome");
        $this->setBrowserUrl($this->baseUrl);
        $this->projectName = uniqid('TEST');
    }

    private function cleanProjects()
    {
        $projectsPath = __DIR__.'/../../';
        system('rm -rf '.$projectsPath.'projects/*');
    }

    private function configureProjectCredentials()
    {
        $projectRoot = __DIR__.'/../../';
        $defaultProjectCredentialsFilePath = $projectRoot.'config.php-dist';
        $projectCredentialsFilePath = $projectRoot.'config.php';
        if (file_exists($projectCredentialsFilePath)) {
            unlink($projectCredentialsFilePath);
        }

        if (false === copy($defaultProjectCredentialsFilePath, $projectCredentialsFilePath)) {
            $this->fail('Cannot create project credentials.');
        }
    }

    public function assertLocation($urlPath)
    {
        $url = sprintf('%s%s', $this->baseUrl, $urlPath);

        parent::assertLocation($url);
    }

    /**
     * @test
     */
    public function authorization_on_reserved_pages()
    {
        $this->open(self::ROUTE_INDEX);

        $this->assertLocation(self::ROUTE_LOGIN);
    }

    /**
     * @test
     */
    public function successful_login()
    {
        $this->open(self::ROUTE_LOGIN);

        $userSelector = 'name=username';
        $this->assertElementPresent(
            $userSelector,
            'Username field could not be found.'
        );
        $this->type($userSelector, self::LOGIN);

        $passwordSelector = 'name=passwort';
        $this->assertElementPresent(
            $passwordSelector,
            'Password field could not be found.'
        );
        $this->type($passwordSelector, self::PASSWORD);

        $submitSelector = 'css=input[type="submit"]';
        $this->assertElementPresent(
            $submitSelector,
            'Submit button could not be found.'
        );
        $this->click($submitSelector);

        $this->waitForPageToLoad(self::TIMEOUT);
        $this->assertLocation(self::ROUTE_INDEX);
    }

    /**
     * @test
     * @depends successful_login
     * @depends authorization_on_reserved_pages
     */
    public function logout()
    {
        $this->successful_login();

        $logoutSelector = 'css=.navigation > .index > img';
        $this->assertElementPresent(
            $logoutSelector,
            'Logout button not found'
        );
        $this->click($logoutSelector);

        $this->authorization_on_reserved_pages();
    }

    /**
     * @test
     */
    public function unsuccessful_login()
    {
        $this->open(self::ROUTE_LOGIN);

        $userSelector = 'name=username';
        $this->assertElementPresent(
            $userSelector,
            'Username field could not be found.'
        );
        $this->type($userSelector, self::LOGIN);

        $passwordSelector = 'name=passwort';
        $this->assertElementPresent(
            $passwordSelector,
            'Password field could not be found.'
        );
        $this->type($passwordSelector, 'weird stuff');

        $submitSelector = 'css=input[type="submit"]';
        $this->assertElementPresent(
            $submitSelector,
            'Submit button could not be found.'
        );
        $this->click($submitSelector);

        $this->waitForPageToLoad(self::TIMEOUT);
        $this->assertLocation(self::ROUTE_LOGIN);
    }

    /**
     * @group wip
     * @test
     * @depends successful_login
     */
    public function create_new_project()
    {
        $this->successful_login();

        $this->open(self::ROUTE_INDEX);
        $openNewProjectFormSelector = "id=addProject";
        $this->assertElementPresent(
            $openNewProjectFormSelector,
            'Button for opening the "New Project" form not found.'
        );
        $this->click($openNewProjectFormSelector);

        $projectNameSelector = "id=newProject";
        $this->waitForElementPresent(
            $projectNameSelector,
            'Field for "Project Name" not found.'
        );
        $this->type($projectNameSelector, $this->projectName);

        $confirmNewProjectSelector = "id=np";
        $this->waitForElementPresent(
            $confirmNewProjectSelector,
            'Button "Confirm new project" not found.'
        );
        $this->click($confirmNewProjectSelector);
        $this->waitForPageToLoad(self::TIMEOUT);

        $projectListSelector = 'css=div.project-list';
        $this->assertElementPresent(
            $projectListSelector,
            'Project list not found.'
        );

        $projectListSelector = 'css=div.project-list a';
        $this->assertElementPresent(
            $projectListSelector,
            'No projects on project list found.'
        );
        $this->assertCssCount(
            $projectListSelector,
            $count = 1,
            sprintf('Expected %d element(s) on Project List.', $count)
        );

        $firstProjectOnProjectListSelector = "css=.project-list .project-list-item .item-info";
        $this->assertElementPresent(
            $firstProjectOnProjectListSelector,
            'First project on project list could not be found.'
        );
        $this->assertElementContainsText(
            $firstProjectOnProjectListSelector,
            $this->projectName
        );
    }

    /**
     * @test
     * @depends successful_login
     * @depends create_new_project
     */
    public function remove_existing_project()
    {
        $this->create_new_project();

        $this->open(self::ROUTE_INDEX);
        $removeProjectSelector = "css=div.p_delete";
        $this->assertElementPresent(
            $removeProjectSelector,
            'Remove project button not found.'
        );
        $this->click($removeProjectSelector);

        $removeConfirmationMessage = '/^Do you really want to delete the project[\s\S]$/';
        $this->assertTrue(
            (bool) preg_match($removeConfirmationMessage, $this->getConfirmation())
        );

        $this->waitForPageToLoad(self::TIMEOUT);
        $projectListSelector = 'css=div.project-list';

        $this->assertElementNotContainsText(
            $projectListSelector,
            $this->projectName
        );
    }

    /**
     * @test
     * @depends successful_login
     * @depends create_new_project
     * @depends remove_existing_project
     */
    public function create_a_new_markdown_node()
    {
        $markdownText = 'A **markdown** node.';
        $expectedResult = 'A markdown node.';
        $this->create_new_project();

        // Enter on new project
        $this->open(self::ROUTE_INDEX);
        $this->waitForPageToLoad(self::TIMEOUT);
        $this->click("css=div.item-info");
        $this->waitForPageToLoad(self::TIMEOUT);
        // Create a new node
        $this->click("id=holder");
        $this->type("id=addfield", $markdownText);

        $addNodeSelector = 'css=.addPost';
        $this->waitForElementPresent(
            $addNodeSelector,
            'Confirm node creation element missing.'
        );
        $this->click($addNodeSelector);
        $this->waitForPageToLoad(self::TIMEOUT);
        $this->assertElementContainsText(
            'css=#p0 > .ncontent > .content',
            $expectedResult,
            'A rendered markdown was expected as a result.'
        );
    }

    /**
     * @test
     * @depends successful_login
     * @depends create_new_project
     * @depends create_a_new_markdown_node
     */
    public function remove_text_node()
    {
        $this->create_a_new_markdown_node();

        $removeNodeSelector = "css=#p0 > .ncontent > .actions > .delete-big";
        $this->assertElementPresent(
            $removeNodeSelector,
            'Remove an existing node element not present.'
        );
        $this->click($removeNodeSelector);
        $confirmationMessage = '/^Do you really want to delete this node[\s\S]$/';
        $this->assertTrue(
            (bool) preg_match($confirmationMessage,$this->getConfirmation())
        );

        $this->waitForPageToLoad(self::TIMEOUT);
        $nodeSelector = 'css=#project .row';
        $this->assertCssCount(
            $nodeSelector,
            1,
            'At least one node exists when none was expected.'
        );
    }

    /**
     * @test
     * @depends successful_login
     * @depends create_new_project
     */
    public function create_image_node()
    {
        $this->create_new_project();

        // Enter on new project
        $this->open(self::ROUTE_INDEX);
        $this->waitForPageToLoad(self::TIMEOUT);
        $this->click("css=div.item-info");
        $this->waitForPageToLoad(self::TIMEOUT);

        // Put file on form
        $localFilePath = __DIR__.'/resources/image-big.jpg';
        $this->type('id=uup', $localFilePath);

        $this->waitForPageToLoad(self::TIMEOUT);

        // Assertions
        $nodeSelector = 'css=#project .row';
        $this->assertCssCount(
            $nodeSelector,
            2,
            'At least one node of image should be present.'
        );
    }

    /**
     * @test
     * @depends successful_login
     * @depends create_new_project
     * @depends create_image_node
     */
    public function remove_image_node()
    {
        $this->create_image_node();

        // Enter on new project
        $this->open(self::ROUTE_INDEX);
        $this->waitForPageToLoad(self::TIMEOUT);
        $this->click("css=div.item-info");
        $this->waitForPageToLoad(self::TIMEOUT);

        $projectNodesSelector = 'css=#project';
        $this->assertElementPresent(
            $projectNodesSelector,
            'Nodes of project element not present.'
        );

        $firstNodeSelector = "${projectNodesSelector} #p0";
        $this->assertElementPresent(
            $firstNodeSelector,
            'First (image) node should be present.'
        );

        $removeFirstNodeSelector = "${firstNodeSelector} .delete-big";
        $this->assertElementPresent(
            $removeFirstNodeSelector,
            'Remove button for first (image) node should be present.'
        );

        $this->click($removeFirstNodeSelector);
        $this->waitForPageToLoad(self::TIMEOUT);

        $nodeSelector = "${projectNodesSelector} .row";
        $this->assertCssCount(
            $nodeSelector,
            1,
            'No node should be present at project page after removing the only existing node.'
        );
    }
}
