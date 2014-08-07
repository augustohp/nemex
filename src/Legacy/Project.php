<?php

namespace Nemex\Legacy;

use Michelf\MarkdownExtra;

class Project
{
    private $project_id = '';
    private $name = '';
    private $plainname = '';
    private $num_nodes = 0;
    private $nodes = array();
    private $markdownParser = null;

    public function __construct($pname)
    {
        $this->setProjectName($pname);
        $this->markdownParser = new MarkdownExtra;
    }

    private function setProjectName($name = '')
    {
        $expected_base_dir = realpath(NEMEX_PATH.'projects/');
        $path_info = pathinfo(realpath(NEMEX_PATH.'projects/'.$name));

        if (isset($path_info['dirname']) AND $path_info['dirname'] === $expected_base_dir) {
            $this->name = $path_info['basename'];
            return;
        }

        throw new \Exception('Invalid project name, or project doesn not exists.');
    }

    function getId()
    {
        return $this->project_id;
    }

    function getName()
    {
        return $this->name;
    }

    function getNumNodes()
    {
        $nodes_in_folder = scandir(NEMEX_PATH.'projects/'.$this->name);

        $nodes_in_folder = array_filter($nodes_in_folder, create_function('$node_name', '
            return (substr($node_name, 0, 1) !== "." AND ! in_array($node_name, ["index.php", "big"]));
        '));

        $this->num_nodes = count($nodes_in_folder);
        return $this->num_nodes;
    }


    function showProject()
    {
        $twig = Wilber::getTwig();
        $counter = 0;
        $renderedNodes = [];
        foreach ($this->getNodes() as $node) {
            $nodeInformation = [
                "id" => $counter,
                "date" => $node->getDate(),
                "type" => ($node->getType() == 'img') ? 'image' : 'markdown' ,
                "name" => $node->getName()
            ];

            if($nodeInformation['type'] == 'markdown') {
                $nodeInformation["html"] = $this->markdownParser->transform($node->getContent());
            } else {
                $nodeInformation['url'] = sprintf('projects/%s/%s', $this->name, $node->getName());
                $nodeInformation['url_big'] = sprintf(NEMEX_PATH.'projects/%s/big/%s', $this->name, $node->getName());
            }
            $counter++;
            $renderedNodes[] = $twig->render('component/node.html', $nodeInformation);
        }

        $projectInformation = [
            'name' => $this->getName(),
            'total_nodes' => $counter,
            'content' => implode(PHP_EOL,$renderedNodes)
        ];

       return $twig->render('component/project.html', $projectInformation);
    }

    public function deleteProject()
    {
        foreach (new DirectoryIterator(NEMEX_PATH.'projects/'.$this->name.'/big') as $fileInfo) {
            if($fileInfo->isDot() || !$fileInfo->isFile()) continue;
            unlink(NEMEX_PATH.'projects/'.$this->name."/big/".$fileInfo->getFilename());
        }
        rmdir(NEMEX_PATH.'projects/'.$_GET['project'].'/big');

        foreach (new DirectoryIterator(NEMEX_PATH.'projects/'.$this->name) as $fileInfo) {
            if($fileInfo->isDot() || !$fileInfo->isFile()) continue;
            unlink(NEMEX_PATH.'projects/'.$this->name."/".$fileInfo->getFilename());
        }
        rmdir(NEMEX_PATH.'projects/'.$this->name);
    }

    private function getNodes()
    {
        $files = array();
        $f = glob(NEMEX_PATH.'projects/'.$this->name.'/{*.jpg,*.gif,*.png,*.md,*.txt}', GLOB_BRACE);
        if (is_array($f) && count($f) > 0) {
            $files = $f;
        }

        sort($files);
        $files = array_reverse($files);
        $counter = 0;
        $nodes = [];
        foreach ($files as $entry) {
            array_push($nodes, new Node($entry, $this->name) );
        }

        return $nodes;
    }

    public function getTitleImage()
    {
        foreach ($this->getNodes() as $node) {
            if($node->getType() == 'img') {
                return $this->name.'/'.$node->getName();
            }
        }
    }
}

