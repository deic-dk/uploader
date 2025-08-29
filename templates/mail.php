<?php

if(count($_['filenames'])>1){
print_unescaped($l->t('Hi there,<br><br>%s has shared the following files with you:<br>',
array($_['user_displayname'])));
}
else{
	print_unescaped($l->t('Hi there,<br><br>%s has shared the following file with you:<br>', array($_['user_displayname'])));
}

print_unescaped("<ul>");
for($i=0; $i<count($_['filenames']); ++$i){
	print_unescaped("<li><a href=\"".$_['links'][$i]."\">".$_['filenames'][$i]."</a></li>");
}
print_unescaped("</ul>");

if(isset($_['expiration'])){
	print_unescaped('<br>');
	p($l->t("The share will expire on %s.", array($_['expiration'])));
}
print_unescaped('<br>');
p($l->t("Danish students and researchers can enjoy unlimited file sharing at sciencedata.dk"));
?>

<br><br>
--
<br><br>
<?php p($theme->getName()); ?> - <?php p($theme->getSlogan()); ?>
<br>
<a href="<?php p($theme->getBaseUrl()); ?>"><?php p($theme->getBaseUrl());?></a>
