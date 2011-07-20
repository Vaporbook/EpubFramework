


EpubFramework, v. 0.1
================================================================
A simple, easy, command-line framework for creating Epub books.
----------------------------------------------------------------
(c) Aaron Miller, MIT license.



***What is a framework?***

We'll spare you this explanation:

http://en.wikipedia.org/wiki/Software_framework

And merely say that this is a small collection of quick-and-dirty bash scripts, a validation library, and some bare-bones e-book package directories with specialized CSS baked into them (read: production templates), which can be used as nice starting points for a text-editor driven development of epub files.

***What will this do for me?***

In short, not much. Unless that is, you find the process of properly zipping up OPS directories into epub files and validating them rather tedious and unnecessarily time-consuming -- in that case, this will save you some time and headaches and reduce the risk of dumb mistakes costing you more time and headaches (not that you're dumb, or any dumber than I am, but in the cold circuitry-ridden regard of the machine, we're all bleeding idiots).

***OK, how do I use it?***

The initial version has three important directories in it. First, the scarily named "skeleton" directory, contains your bare bones OPS templates. Each one of these is special, and more can be added, so you can add your own special-er versions of your OPS templates, depending on how you like to produce your books. The two beginning skeletons are "default" and "bibliotype." The "default" skeleton has a sparse but book-oriented CSS file in it, a standard OPS directory structure, and some OPS and OCF metadata files. There are also a few default content files to use as starting points. The "bibliotype" skeleton is very similar, except the CSS it uses is Craig Mod's bibliotype CSS framework.

Second, the "ops-src" directory is your working directory for all the book content you'll be editing and adding before creating an epub file. Once the epub files are created, they go into the "epub-build" directory.

***What's the basic workflow?***

The basic workflow is complementary to anyone comfortable with the command line, with source code or text editors and IDEs, and who does some or all of their content development and QA in a web browser. 

Like any command line tool, you'll need to make sure your environment is set up to search for the command. This is usually done by making sure the full path to the 'bin' directory that the scripts reside in is included in a PATH variable.

The process of creating, editing and outputting a new book is as follows:

1. From the framework directory, run the "newepub.sh" command. An example would be:
 		
	newepub.sh myEpubBook bibliotype
		
	This creates a new OPS structure in the "ops-src" directory, using the "bibliotype" skeleton as a starting point.
	
2. Edit the OPS sources, previewing your content in a browser as you do. Use the index.html file as an HTML version of your table of contents for previewing purposes.

3. When you're satisfied with the content, and ready to deploy to devices, use the mkepub.sh script, as in:

	 mkepub.sh myEpubBook
	
	
4. The mkepub.sh script will also run a validation check on the resulting epub file, but if you want to validate the epub independently, you can use the ckepub.sh script to do so.


***What's included in each of the two built-in skeleton directories?***

The files in the skeleton directories follow both the Epub spec and best practices that have evolved over the last 2-3 years. Here's an overview:

	* <meta name="cover"/> element to identify cover image file
	* cover.html as content doc for cover image file
	* blank cover.png file to hold cover image
	* OPF with required and some optional metadata
	* NCX with minimal table of contents
	* index.html file with browser-readable, linked table of contents
	* book-device friendly CSS stylesheet
	* fonts directory
	
	
***OMG, what are the requirements to make this work?***

You will need a Linux, bash command line in your terminal. Later versions may require a CLI install of PHP. You will also need a Java JRE (1.5) for the validation library to work. The scripts also use the command line version of zip, so you'll need that too (it's included in most Linux installs).


***My programs are in weird places?***

Sorry. You'll need to edit the file etc/config.sh, where I tried to put any system-specific paths or otherwise customizable names.


