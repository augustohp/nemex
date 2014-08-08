<?php

class EndToEndTest extends \PHPUnit_Extensions_SeleniumTestCase
{
    const TIMEOUT = 10000;
    const BASE_URL = "http://www.192.168.42.03.xip.io";
    const PROJECT_NAME = 'Wunderbar';
    const LOGIN = 'penny';
    const PASSWORD = 'cat';

    const ROUTE_LOGIN = '/login.php';
    const ROUTE_INDEX = '/index.php';

    protected function setUp()
    {
        $this->cleanProjects();

        //$this->setSpeed(500);
        $this->setBrowser("*chrome");
        $this->setBrowserUrl(self::BASE_URL);
    }

    private function cleanProjects()
    {
        $projectsPath = __DIR__.'/../../';
        system('rm -rf '.$projectsPath.'projects/*');
    }

    public function assertLocation($urlPath)
    {
        $url = sprintf('%s%s', self::BASE_URL, $urlPath);

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
     * @depend s successful_login
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
        $this->type($projectNameSelector, self::PROJECT_NAME);

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
            self::PROJECT_NAME
        );
    }

    /**
     * @test
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
            self::PROJECT_NAME
        );
    }

    /**
     * @test
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
        $this->click("css=div.item-info");
        $this->waitForPageToLoad(self::TIMEOUT);
        // Create a new node
        $this->click("id=holder");
        $this->type("id=addfield", $markdownText);

        $addNodeSelector = 'id=createPost';
        $this->waitForElementPresent(
            $addNodeSelector,
            'Confirm node creation element missing.'
        );
        $this->click($addNodeSelector);
        $this->waitForPageToLoad(self::TIMEOUT);
        $this->assertElementContainsText(
            'css=#p-0 > .ncontent > .content',
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

        $removeNodeSelector = "css=#p-0 > .ncontent > .actions > .delete-big";
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
}
