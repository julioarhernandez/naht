Block in block
==============

CONTENTS OF THIS FILE
---------------------

* Introduction
* Requirements
* Installation
* Configuration
* Maintainers

INTRODUCTION
------------

The Block in block module adds the ability to insert the contents of a block
into another block within the same theme and region ("target block").

A string within the target block, like a paragraph tag (`<p>`), is used to
identify where to insert the block. A method is provided to define which
occurrence(s) of the string to insert at, and whether to insert it before
the string, after it, or to replace it entirely. When the target block is the
"Main page content", it is possible to define the node types and view modes
that the block will be inserted into.

For a full description of the project visit the project page:
<https://www.drupal.org/project/block_in_block>

For more detailed information about how to use the module, as well as answers to
frequently asked questions, visit the module's online documentation:
<https://www.drupal.org/docs/7/modules/block-in-block>

To submit bug reports and feature suggestions, or to track changes:
<https://www.drupal.org/project/issues/block_in_block>

REQUIREMENTS
------------

This module requires the following modules:

* [Block](https://www.drupal.org/project/block)

INSTALLATION
------------

Install as you would normally install a contributed Drupal module:

* Also See <https://drupal.org/documentation/install/modules-themes/modules-7>
for further information.

CONFIGURATION
-------------

* Visit the configuration page for the block being inserted, at
Administration >> Structure >> Blocks
* Click on the "Within another block" tab in the Visibility Settings group.

MAINTAINERS
-----------

Current maintainers:

* Ben Greenberg (runswithscissors) - <http://drupal.org/user/3188825>
