<?php
if(count($_['filenames'])>1){
	print_unescaped($l->t("Hi there,\n\n%s has shared the following files with you:\n", array($_['user_displayname'])));
}
else{
	print_unescaped($l->t("Hi there,\n\n%s has shared the following file with you:\n", array($_['user_displayname'])));
}

for($i=0; $i<count($_['filenames']); ++$i){
	print_unescaped($_['filenames'][$i]." : ".$_['links'][$i]."\n");
}
print_unescaped("\n");

if ( isset($_['expiration']) ) {
	print_unescaped($l->t("The share will expire on %s.", array($_['expiration'])));
	print_unescaped("\n\n");
}
p($l->t("Danish students and researchers can enjoy unlimited file sharing at sciencedata.dk.\n\n"));
?>

--
<?php print_unescaped("\n\n"); ?>
<?php p($theme->getName() . ' - ' . $theme->getSlogan()); ?>
<?php print_unescaped("\n".$theme->getBaseUrl()); ?>
