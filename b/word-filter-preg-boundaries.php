<?php
//init
$raw ='
At the same time in England, a number of illustrious collections were being formed. Many of these collections are well known and need only to be mentioned here. Among these were the collection of Ole Worm, whose Museum Wormianum achieved great fame. Another collection of rarities was preserved at South Lambeth by Elias Ashmole. Mr Ashmole, a botanist, presented his collection to his friend and neighbor Samuel Dule (author of the Pharmacologia) to whom it was delivered one week before Mr. Dule\'s death.

Through the second half of the eighteenth century and into the nineteenth century it became fashionable to donate these collections to budding public institutions. The first of these "public" museums were little more than formalized displays of private collections of rarities and curios, often with little regard for any meaningful order of display. These institutions, though public in name, were accessible in fact only to the cognoscenti and then only by appointment, in small groups, and for limited periods of time.

However, at the same time, in the city of Philadelphia in America, Charles Willson Peale was forming a museum that was to become a model for the institution for years to come. Mr. Peale\'s Museum was open to all people (including children and the fair sex) and was philosophically grounded in the thoughts of Jean Jacques Rousseau. Peale fervently believed that teaching is a sublime ministry inseparable from human happiness, and that the learner must be led always from familiar objects toward the unfamiliar - guided along, as it were, a chain of flowers into the mysteries of life.

"Rational amusement" was the Peale Museum\'s instrument but also, by curious irony, its eventual undoing. Imitators sprang up almost at once. A collection of oddities, unencumbered by scientific purpose was found to be "good business". Tawdry and specious museums soon appeared in almost every American city and town. This unsavory tendency finally reached its peak with Barnum, who in the end obtained, scattered, and ultimately incinerated, the Peale collections.

The Museum of Jurassic Technology traces its origins to this period when many of the important collections of today were beginning to take form. Many exhibits which we today have come to know as part of the Museum were, in fact, formally part of other less well known collections and were subsequently consolidated into the single collection which we have come to know as The Museum of Jurassic Technology and thus configured, received great public acclaim as well as much discussion in scholastic circles.
';

$words = array('museum','origins','monkeys','bob','alligator','business','turtles','scholas','zip','zap','zoom','races');

$patterns = array();
$replacements = array();
foreach ($words as $word) {
    $patterns[] = '/\b' . $word . '/ui';
    $replacements[] = $word[0] . str_repeat('*', strlen($word) - 1);
    $patterns[] = '/' . $word . '\b/ui';
    $replacements[] = $word[0] . str_repeat('*', strlen($word) - 1);
}

// time
$matches = preg_replace($patterns, $replacements, $raw);
// ignore
var_dump($matches);
