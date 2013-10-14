<?php
$e = new ReflectionExtension('gearman');
print "<?php\n\n// Gearman Version: " . $e->getVersion() . "\n\n";
foreach ($e->getClasses() as $c) {
  print 'class ' . $c->name . " {\n";
  foreach ($c->getMethods() as $m) {
    print '  ';
    if ($m->isPublic()) {
        print 'public function ';
    } elseif ($m->isProtected()) {
        print 'protected function ';
    } elseif ($m->isPrivate()) {
        print 'private function ';
    }
    print $m->name . '(';
    $sep = '';
    foreach ($m->getParameters() as $p) {
      print $sep;
      $sep = ', ';
      if ($p->isOptional())
        print '$' . $p->name . ' = null' ;
      else
        print '$' . $p->name;
    }
    print "){}\n";
  }
  print "}\n\n";
}
