<?php

App::uses('SimpleHtmlDomSource', 'SimpleHtmlDom.Model/Datasource');

class SampleTest extends CakeTestModel {
	public $useDbConfig = 'htmldom';
	public $source = '<div>default config in <span>Model</span></div>';
}

class SimpleHtmlDomSourceTest extends CakeTestCase {
	public $Model = null;
	public $config = array(
		'datasource' => 'SimpleHtmlDom.SimpleHtmlDomSource',
		'source' => '<div>default config in <span>database.php</span></div>',
	);
	public $configNoSource = array(
		'datasource' => 'SimpleHtmlDom.SimpleHtmlDomSource',
	);

	public function startTest() {}
	public function endTest() {
		ClassRegistry::flush();
	}

	public function testFindFirst() {
		$config = $this->config;
		ConnectionManager::create('htmldom', $config);
		$this->Model = ClassRegistry::init('SampleTest');
		$isHtml = true;
		// set condition
		$conditions = '<div>custom in <span>conditions</span></div>';
		$expected = $conditions;
		$html = $this->Model->find('first', compact('conditions', 'isHtml'));
		$this->assertSame($expected, $html->innertext, 'custom source');
		// do not set condition
		$html = $this->Model->find('first', compact('isHtml'));
		$expected = $this->Model->source;
		$this->assertSame($expected , $html->innertext, 'Model\'s source');
	}

	public function testFindFirstNotUsingModelSource() {
		$config = $this->config;
		ConnectionManager::create('htmldom', $config);
		$this->Model = ClassRegistry::init('SampleTest');
		unset($this->Model->source);
		$isHtml = true;
		// do not use Model's source
		$html = $this->Model->find('first', compact('isHtml'));
		$expected = $config['source'];
		$this->assertSame($expected , $html->innertext, 'DbConfig source');
	}

	public function testFindAll() {
		$config = $this->config;
		ConnectionManager::create('htmldom', $config);
		$this->Model = ClassRegistry::init('SampleTest');
		$isHtml = true;
		// set condition
		$conditions = array(
			'<div>1st <span>condition</span></div>',
			'<div>2nd <span>condition</span></div>',
		);
		$htmls = $this->Model->find('all', compact('conditions', 'isHtml'));
		// 1st
		$expected = $conditions[0];
		$this->assertSame($expected, $htmls[$conditions[0]]->innertext, '1st of findAll');
		// 2nd
		$expected = $conditions[1];
		$this->assertSame($expected, $htmls[$conditions[1]]->innertext, '2nd of findAll');
	}
}
