<?php
	echo "
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml' lang='en'>
	<head>
		<title>EWU Advancement Programming Exam</title>
  
		<link href='https://ape.compsci.ewu.edu/phpAPE/css/reset.css'rel='stylesheet' type='text/css' />
		<link href='https://ape.compsci.ewu.edu/phpAPE/css/ewuaux.css' rel='stylesheet' type='text/css' />
		<link href='https://ape.compsci.ewu.edu/phpAPE/css/ape.css' rel='stylesheet' type='text/css' />
		<link href='https://ape.compsci.ewu.edu/phpAPE/css/jquery-ui/jquery-ui-1.8.11.custom.css' rel='stylesheet' type='text/css' />
		<link rel='icon' href='https://ape.compsci.ewu.edu/phpAPE/favicon.ico' type='image/x-icon' />
			
		<script type='text/javascript' src='https://gc.kis.v2.scr.kaspersky-labs.com/2F476567-E702-8240-875B-BB55615DFB3E/main.js' charset='UTF-8'></script><script type='text/javascript' src='https://ape.compsci.ewu.edu/phpAPE/js/jquery-1.5.2.min.js'></script>
		<script type='text/javascript' src='https://ape.compsci.ewu.edu/phpAPE/js/jquery-ui-1.8.11.custom.min.js'></script>
		<script type='text/javascript' src='https://ape.compsci.ewu.edu/phpAPE/js/jquery.selectbox.js'></script>
		<script type='text/javascript' src='https://ape.compsci.ewu.edu/phpAPE/js/search-default-text.js'></script>
		<script type='text/javascript'>//<![CDATA[
			$(function() {
				$('#selectbox').selectbox();
				$('input.date').datepicker();
			});
		//]]></script>
	</head>

	<body id='cas' class='ewu-default fl-theme-iphone'>
		<!--START HEADER SKIN-->
		<div id='wrapper'>
			<div id='auxheader'>
				<h1><a href='http://www.ewu.edu'>Eastern Washington University</a></h1>
				
				<div style='clear:both'></div>
			</div>
		<div id='nav' class='brdcrumbs'>
			<h1>EWU Advancement Programming Exam</h1>
			<ul>
				<li><a href='studentp2.php'>APE Info</a></li><li><a href='studentp1.php'>Home</a></li>			</ul>
		</div>
		<div id='center-wrapper'>
			<div id='content'>
				<!-- END HEADER SKIN -->

				
				
				<div class='alert'>

<h2>EXAM ENVIRONMENT:</h2>
<ul>
<li>Linux Operating System</li>
<li>Editors</li>
<ul>
<li>JGrasp</li>
<li>GEdit</li>
<li>Eclipse</li>
</ul>
<li>JDK 8.0 (1.8.x)</li>
<li>Java API documentation</li>
<li>No Internet access</li>
<li>No notes or texts allowed</li>
<li>The exam is now a Test Driven Design Exam where you are writing code based on the provided tests</li>
</ul>
<h2>EXAM SPECIFICS</h2>
<p>The Computer Science Department has determined that the distribution of points within the exam will be as follows:</p>
<ul>
<li><strong>General Program Design: 30%</strong><br />
<ul>
<li>Penalty points can be assessed in this category for grossly inefficient code.</li>
<li>File input and output</li>
<li>Exception handling</li>
<li>Order-related manipulations, such as <strong><em>addOrdered</em></strong>, <strong><em>sort</em></strong>, and <strong><em>search</em></strong></li>
</ul>
</li>
<li><strong>Data Abstraction and Class Design: 30%</strong>
<ul>
<li>Extending a class</li>
<li>Overriding a method, such as <strong><em>toString</em></strong></li>
<li>Creating a class</li>
<li>Accessing super class methods and/or fields</li>
<li>Implementing an interface, such as <strong><em>Comparable</em></strong></li>
</ul>
</li>
<li><strong>Linked List Manipulation: 20%</strong>
<ul>
<li>This will be a non-circular singly-linked list, either with or without a header/sentinel node.</li>
<li>Operations will be <strong><em>chosen</em></strong>from the following list.
<ul>
<li>Adding a node &mdash; all cases (to an empty list, to a non-empty list, at front, at back, in the middle)</li>
<li>Remove a node &mdash; all cases (based on index, based on Object equality, from front, from back, from middle)</li>
<li>Retrieve an item based on index or some other criterion (such as maximum or minimum).</li>
<li>Order-related manipulations, such as addOrdered and sort, may be exemplified within the linked list.</li>
<li>Generate a sublist, based on some criterion or criteria.</li>
</ul>
</li>
</ul>
</li>
<li><strong>Recursion: 20%</strong>
<ul>
<li>Simple recursive methods to accomplish a task, which may or may not be list related. Examples would be printing a linked list in reverse, computation of factorials, and other recursive algorithms at the level of those covered in Programming Principles I and II and in Data Structures.</li>
<li>An example you probably <em>haven't</em> seen. <a href='http://penguin.ewu.edu/cscd300/Topic/Recursion/AdditiveSquares.java'>AdditiveSquares.java</a></li>
</ul>
</li>
</ul>
<h2>EXPANDED SPECIFICS</h2>
<p>An expanded list of topics follows.&nbsp; You should make sure you are proficient in each area to ensure success.</p>
<ul>
<li>Linked list utilization (a subset of the below items will be required)
<ul>
<li>insert (front, back, in order)</li>
<li>delete (front, back, specific item)</li>
<li>print</li>
<li>traverse</li>
<li>sort(you must write the code, unless otherwise specified)&nbsp; Note, however, that you may be asked to do array-oriented sorting.</li>
<li>build a sub-list from an existing list</li>
<li>utilize a dummy head node &mdash; or <strong><em>not</em></strong> utilize one</li>
</ul>
</li>
<li>Basic recursion (like using recursion to traverse a linked list in some form, or print the contents of a linked list in reverse order)</li>
<li>File input and output
<ul>
<li>Input via a Scanner object
<ul>
<li>any input file will be well formed</li>
<li>know how to stop when end of file is reached</li>
<li>remember to close file when finished</li>
</ul>
</li>
<li>Output via PrintWriter
<ul>
<li>know how to format output (decimal points, columns, etc.)</li>
<li>remember to close output file when finished</li>
</ul>
</li>
</ul>
</li>
<li>Inheritance
<ul>
<li>Understand base / derived relationship</li>
<li>know how to override methods to enable polymorphic behavior</li>
<li>understand abstract classes and interfaces</li>
<li>know how to derive a class from an existing class</li>
<li>know how to call base/parent/super class methods (requires use of keyword super)</li>
</ul>
</li>
<li>Comparable interface
<ul>
<li>know how to implement for a given class, including multiple-key comparisons</li>
<li>know how to utilize when searching and sorting (i.e. call the compareTo method)</li>
</ul>
</li>
<li>Basic exception handling
<ul>
<li>know how to designate a section of code as possibly throwing an execption (via a try block)</li>
<li>know how to handle and exception (via a catch block)</li>
<li>know how to propagate an exception from a method (by using throws and the end of the method signature)</li>
<li>know how to generically handle an exception (use Exception to do this)</li>
</ul>
</li>
<li>toString
<ul>
<li>know how to implement properly for a given class</li>
<li>know how to utilize an existing toString provided by a class (recall that it returns a String representing data in the object you'd like printed and that it is automatically invoked whenever you try and print an object using print, println, or printf with the '%s' format specifier)</li>
</ul>
</li>
</ul>
<p>This exam covers material very similar to that in the warm-up assignment in Data Structures (CSCD300), or possibly the warm-up assignment and the second programming assignment.</p>
<p>You will not have to start from scratch on the exam.&nbsp; You will be given a (previously) working program that has had functionality removed.&nbsp; It is your job to reinsert that functionality.&nbsp; For the most part, this will be done via adding methods.&nbsp; You will be told in some way what methods are required.&nbsp; You may be asked to write a class in its entirety.&nbsp; However, if you are asked to write an entire class the amount of code you will be required to write is not intended to be &ldquo;large.&rdquo;&nbsp; Questions will be asked in &ldquo;parts&rdquo; that can be done separately.&nbsp; This means if you mess up on part 1, and part 2 requires something from part 1, you will not need to have completed part 1 successfully to do part 2.</p>
<h3>Problem Areas Identified From Previous Exams</h3>
<ul>
<li>Not following directions</li>
<li>Not reading the specifications in their entirety &mdash; make sure you read all directions before you begin writing code.&nbsp; A prime example is ignoring specifications related to whether the linked list does or does not have a header/sentinel node.</li>
<li>Not placing files in specified location</li>
<li>Not naming files as specified</li>
<li>Modifying files that were to be left alone</li>
<li>Unfamiliarity with keywords in Java required for basic things like inheritance (extends) and interface use (implements)</li>
<li>Spending too much time on one item</li>
<li>Inability to implement simple recursion</li>
</ul>
<h3>Basic strategies you should use</h3>
<ol>
<li>Stub the methods out that you have to write, then fill them in with useful code when you are ready</li>
<li>Read the directions carefully, then look at the sample input and output before you begin writing code</li>
<li>Comment out items that don't compile so you can test other parts of your solution (this includes methods you can't get to work and method calls that may occur from elsewhere &mdash; like in the main method of the program).&nbsp; Note, however, that your code will be tested using the original driver file, not your edited one.</li>
</ol>
<h3>Practice Exam</h3>
<ul>
<li><a href='http://penguin.ewu.edu/advancement_exam/practice_exams/W11APE.zip'>Winter 2011 Student Version</a></li>
<li><a title='Sample APE Solution' href='http://penguin.ewu.edu/advancement_exam/practice_exams/W11FullAPE.zip'>Winter 2011 Solution</a></li>
<li><a href='http://penguin.ewu.edu/advancement_exam/practice_exams/W12APE.zip'>Winter 2012 Student Version</a></li>
<li><a title='Summer 2013 Student APE' href='http://penguin.ewu.edu/advancement_exam/practice_exams/Summer2013APE.zip' target='_blank'>Summer 2013 Student Version</a></li>
</ul>
<p>NOTE: This is just a sample exam &mdash; do not expect the exam you take to be formatted in precisely the same way, with the same number of classes, methods, etc. &mdash; this is just for practice and to give you an idea of what to expect.&nbsp; The amount of code you are asked to write on this exam is similar to the amount you will have to write on the actual exam.&nbsp; The topics on this exam are a selection from the list given at the top of this web page; the actual exam you take may have topics not addressed on this exam, but which are a part of the list above.</p>
</div>
<div class='info'><br />
<h2>NOTES</h2>
<ul>
<li><span style='font-size: small; color: #993366;'><strong>Any student requesting accomodations based on DSS, must send an email to tcapaul@ewu.edu at least one week prior to the exam.</strong></span></li>
<li>You are allowed to take the APE only three (3) times without passing.</li>
<li>It is your responsibility to register for the exam online. If you show up to the exam without registering, you may be turned away</li>
<li>Online registration is done through this page.</li>
</ul>
<p>&nbsp;</p>
</div></div>
				<!--START FOOTER SKIN-->
			</div>
		</div>
		<div id='ftr-cleared'>
			<div id='ftr-content'>
				<div id='ftr-cnt-links'>
					<ul>
						<li><a href='http://www.ewu.edu/about'>About</a></li>
						<li><a href='http://www.ewu.edu/contact-ewu'>Contact Info</a></li>
						<li><a href='http://www.ewu.edu/foundation'>Give to EWU</a></li>
						<li><a href='http://www.ewu.edu/visit'>Locations / Map</a></li>
						<li><a href='http://canvas.ewu.edu'>Canvas</a></li>
						<li><a href='http://eaglenet.ewu.edu'>EagleNET</a></li>
						<li><a href='http://www.ewu.edu/library'>Library</a></li>
						<li><a href='http://www.ewu.edu/jobs'>Jobs</a></li>
						<li><a href='http://www.ewu.edu/site-map'>Site Map</a></li>
					</ul>
				</div>
				<div id='ftr-cprght'>&copy; 2010 Eastern Washington University</div>
					<address id='ftr-cnt-addr'>
					<span>Stu Steiner</span> <span><a href='mailto:ssteiner@ewu.edu'>ssteiner@ewu.edu</a></span> <span><a href='http://www.ewu.edu/Privacy-Policy'>Privacy Policy</a></span>
					</address>
				</div>
			</div>
		</div>
		<!--END FOOTER SKIN -->
	</body>
</html>
"
	
?>