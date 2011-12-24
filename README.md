CakePHP simple_html_dom Datasource Plugin
====

*simple_html_dom* is a HTML DOM parser let you manipulate HTML in a very easy way.

http://simplehtmldom.sourceforge.net/


USAGE
----

* download this plugin

```sh
$ git submodule add git://github.com/monsat/CakePHP-SimpleHtmlDom-Plugin.git your_app/Plugin/SimpleHtmlDom
$ ls your_app/Plugin
SimpleHtmlDom
```

* use datasource

```php
# Config/database.php
class DATABASE_CONFIG {
  public $htmldom = array(
		'datasource' => 'SimpleHtmlDom.SimpleHtmlDomSource',
		'source' => 'http://www.example.com/example.html', // URL or path of contents
	);
}
# Model/Sample.php
class Sample extends AppModel {
  public $useDbConfig = 'htmldom';
  public $source = 'http://www.yahoo.co.jp/'; // URL or path of contents if override db config
}
```

* at controller

```php
public function index() {
  $conditions = array('http://www.example.com/a.html', 'http://www.example.com/b.html');
  $htmls = $this->Sample->find('all', compact('conditions'));
  debug($htmls['http://www.example.com/b.html']->plaintext);
}
public function view() {
  $html = $this->Sample->find('first');
  debug($html->plaintext);
}
```

* others
  * isHtml

```php
# at controller
public function index() {
  $conditions = '<ul id="list"><li>1st</li><li>2nd</li></ul>';
  $isHtml = true;
  $html = $this->Sample->find('first', compact('conditions', 'isHtml'));
  debug($html->plaintext);
  $firstChild = $html->find('#list')->first_child();
  debug($firstChild->innertext);
}
```
See also
----
http://simplehtmldom.sourceforge.net/manual.htm