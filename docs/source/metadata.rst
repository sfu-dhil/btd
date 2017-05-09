.. _metadata:

Metadata for Media Files
========================

  Metadata is structured information that describes, explains,
  locates, or otherwise makes it easier to retrieve, use, or manage an
  information resource.  Source: NISO (2004) Understanding
  Metadata. Bethesda, NISO Press.

Metadata is used to describe text, images, video, sound, movement,
objects, or events. It can be used to describe a physical object, a
digital creation, or a digital photograph of a physical object. This
application used a simple standard published by the Dublin Core
Metadata Initiative. It structures the metadata into 15 elements, each
of which describes a different aspect of a resource.

None of the metadata elements are required, and they can all be
repeated if needed.

Most of the text below has been copied from the `Dublin Core Usage
Guide`_. In the text that follows, "resource" means any describable
object, which in the application is a media file.

Title
-----

The name given to the resource. Typically, a Title will be a name by
which the resource is formally known. If in doubt about what
constitutes the title, repeat the Title element and include the
variants in second and subsequent Title iterations.

Creator
-------

An entity primarily responsible for making the content of the
resource. Examples of a Creator include a person, an organization, or
a service. Typically the name of the Creator should be used to
indicate the entity.

Creators should be listed separately, preferably in the same order
that they appear in the publication. Personal names should be listed
surname or family name first, followed by forename or given name. When
in doubt, give the name as it appears, and do not invert.

In the case of organizations where there is clearly a hierarchy
present, list the parts of the hierarchy from largest to smallest,
separated by full stops and a space. If it is not clear whether there
is a hierarchy present, or unclear which is the larger or smaller
portion of the body, give the name as it appears in the item.

If the Creator and Publisher are the same, do not repeat the name in
the Publisher area. If the nature of the responsibility is ambiguous,
the recommended practice is to use Publisher for organizations, and
Creator for individuals. In cases of lesser or ambiguous
responsibility, other than creation, use Contributor.

Subject
-------

The topic of the content of the resource. Typically, a Subject will be
expressed as keywords or key phrases or classification codes that
describe the topic of the resource. Recommended best practice is to
select a value from a controlled vocabulary or formal classification
scheme.

Select subject keywords from the Title or Description information, or
from within a text resource. If the subject of the item is a person or
an organization, use the same form of the name as you would if the
person or organization were a Creator or Contributor.

In general, choose the most significant and unique words for keywords,
avoiding those too general to describe a particular item. Subject
might include classification data if it is available (for example,
Library of Congress Classification Numbers or Dewey Decimal numbers)
or controlled vocabularies (such as Medical Subject Headings or Art
and Architecture Thesaurus descriptors) as well as keywords.

When including terms from multiple vocabularies, use separate element
iterations. If multiple vocabulary terms or keywords are used, either
separate terms with semi-colons or use separate iterations of the
Subject element.

Description
-----------

An account of the content of the resource. Description may include but
is not limited to: an abstract, table of contents, reference to a
graphical representation of content or a free-text account of the
content.

Since the Description field is a potentially rich source of indexable
terms, care should be taken to provide this element when
possible. Best practice recommendation for this element is to use full
sentences, as description is often used to present information to
users to assist in their selection of appropriate resources from a set
of search results.

Descriptive information can be copied or automatically extracted from
the item if there is no abstract or other structured description
available. Although the source of the description may be a web page or
other structured text with presentation tags, it is generally not good
practice to include HTML or other structural tags within the
Description element. Applications vary considerably in their ability
to interpret such tags, and their inclusion may negatively affect the
interoperability of the metadata.

Publisher
---------

The entity responsible for making the resource available. Examples of
a Publisher include a person, an organization, or a service.
Typically, the name of a Publisher should be used to indicate the
entity.

The intent of specifying this field is to identify the entity that
provides access to the resource. If the Creator and Publisher are the
same, do not repeat the name in the Publisher area. If the nature of
the responsibility is ambiguous, the recommended practice is to use
Publisher for organizations, and Creator for individuals. In cases of
ambiguous responsibility, use Contributor.

.. note::

   In this application, Between the Digital is publishing the media
   files and should probably be included as a publisher. There may be
   more than one publisher, especially if the media file has been
   published elsewhere.

Contributor
-----------

An entity responsible for making contributions to the content of the
resource. Examples of a Contributor include a person, an organization
or a service. Typically, the name of a Contributor should be used to
indicate the entity.

The same general guidelines for using names of persons or
organizations as Creators apply here. Contributor is the most general
of the elements used for "agents" responsible for the resource, so
should be used when primary responsibility is unknown or irrelevant.

.. note::

   Anyone listed as a creator should not also be listed as a
   contributor.

Date
----

A date associated with an event in the life cycle of the
resource. Typically, Date will be associated with the creation or
availability of the resource. Recommended best practice for encoding
the date value is to use the YYYY-MM-DD format.

.. note::

   The date or date range that media file was created (not uploaded to
   the website) is a good starting point. If the file was
   significantly altered at a later date, that date should also be
   included.

If the full date is unknown, month and year (YYYY-MM) or just year
(YYYY) may be used.

Type
----

The nature or genre of the content of the resource. Type includes
terms describing general categories, functions, genres, or aggregation
levels for content. Recommended best practice is to select a value
from a the list below.

* Text
* Image
* Sound
* Video
* Catalogue

.. note::

   This field doesn't describe the format of a digital image, only
   that the item *is* a digital image.

.. note::

   If you are describing a catalog of a an exhibition which contains
   images and descriptions of the works, you might want to include
   both Text and Image types.

If the resource is composed of multiple mixed types then multiple or
repeated Type elements should be used to describe the main components.

Format
------

The physical or digital manifestation of the resource. Typically,
Format may include the media-type or dimensions of the
resource. Examples of dimensions include size and duration. Format may
be used to determine the software, hardware or other equipment needed
to display or operate the resource.

In addition to the specific physical or electronic media format,
information concerning the size of a resource may be included in the
content of the Format element if available. In resource discovery
size, extent or medium of the resource might be used as a criterion to
select resources of interest, since a user may need to evaluate
whether they can make use of the resource within the infrastructure
available to them.

When more than one category of format information is included in a
single record, they should go in separate iterations of the element.

.. note::

   The application will attempt to create Format entries when a media
   file is uploaded. You can add additional entries if needed.

Identifier
----------

An unambiguous reference to the resource within a given
context. 

This element can also be used for local identifiers (e.g. ID numbers
or call numbers) assigned by the Creator of the resource to apply to a
particular item. It should not be used for identification of the
metadata record itself.

.. note::

   The application will fill in the identifier with the original name
   of the file as it was uploaded.

Source
------

A Reference to a resource from which the present resource is
derived. The present resource may be derived from the Source resource
in whole or part.

.. note::

   This element doesn't describe how the item was acquired. It isn't
   likely to be used for original works of art.

Language
--------

A language of the intellectual content of the resource. Either a coded
value or text string can be represented here. If the content is in
more than one language, the element may be repeated. Examples include
"en" for English, or "Primarily English, with some abstracts also in French."

Relation
--------

A reference to a related resource. Relationships may be expressed
reciprocally (if the resources on both ends of the relationship are
being described) or in one direction only. If text is used instead of
identifying numbers, the reference should be appropriately
specific. For instance, a formal bibliographic citation might be used
to point users to a particular resource.

Coverage
--------

The extent or scope of the content of the resource. Coverage will
typically include a location (a place name or geographic
co-ordinates), time period (a period label, date, or date range)
or jurisdiction (such as a named administrative entity).

Where appropriate, named places or time periods should be used in
preference to numeric identifiers such as sets of co-ordinates or date
ranges.

Whether this element is used for spatial or temporal information, care
should be taken to provide consistent information that can be
interpreted by human users. For most applications, place names or
coverage dates might be most useful.

Rights
------

Information about rights held in and over the resource. Typically a
Rights element will contain a rights management statement for the
resource, or reference a service providing such information. 

The Rights element may be used for either a textual statement or a URL
pointing to a rights statement, or a combination, when a brief
statement and a more lengthy one are available.

.. _`Dublin Core Usage Guide`:
  http://dublincore.org/documents/usageguide/elements.shtml
