btd
===

A Symfony project created on March 2, 2017, 1:40 pm.

TO DO
=====

The Person entities URL attribute needs to be a list.

People can have bios, and can also have artist statements which are associated
with an artwork or project.

Connect people to projects in a meaningful way. It should be easy to add
individuals to projects (A is a presenter at B, C exhibited at D, etc).

Consider adding pages to projects (there may be many different pages of
context associated with a project - probably the same with artworks).

Define thumb, small, medium, and large image sizes. Keep the originals.

Add an easy way of associating images with projects, people, and artworks.

eventually need a way to convert pdfs to images. Imagemagick:
convert app/data/uploads/9/9fa3ea126268da9f87e5b52b1997f201.pdf[0] -resize 50% p.png

And videos. Imagemagick+ffmpeg:
$ convert app/data/uploads/9/9fa3ea126268da9f87e5b52b1997f201.mp4[0] -resize 50% puppy.png

Etc.
====

Video.js is used to play audio and video files, if the browser supports 
the file type. 

PDFObject.js is used to embed PDFs.

Requirements
============

imagick PHP PECL extension
gs to read PDF files.
