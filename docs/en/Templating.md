# Templating

Bootstrap is a widely used css framework, providing many components and a grid
enabling one page to be used across multiple screen sizes. In the default
form, these blocks embrace the section-structure by rendering every block
as a section on the frontend. This means, they expect to be placed as a
full-width component.

If you have special needs, for example a a layout with a sidebar, you will have
to adapt the holder and possibly the element template

## Change Holder Template
you can provide a custom holder by placing this file in your project:
```
templates/DNADesign/Elemental/Layout/SectionHolder.ss
```
You will have to handle background yourself, have a look at the
[default `SectionHolder.ss`](https://github.com/syntro-opensource/silverstripe-elemental-bootstrap-baseitems/blob/master/templates/DNADesign/Elemental/Layout/SectionHolder.ss)
template to get a feel for how you can do this.


## Use a custom holder
If you need to use a custom holder template, you can specify this by creating
```
templates/Syntro/BootstrapElemental/Layout/MyElementHolder.ss
```
and configuring your element like so (or in your child class):
```yaml
Your\Element\Class:
    controller_template: MyElementHolder
```

## Change the look of a block
You can easily overwrite templates for blocks by generating template files in your
theme/project corresponding to their namespace. Have a look at the `templates/`
directory of the module providing the block in question.


## Use styles
Styles are a functionality inherited by [`dnadesign/silverstripe-elemental`](https://github.com/dnadesign/silverstripe-elemental#style-variants)
and work in the same way. By default, we are using the BEM-Model for our blocks,
where styles are communicated by adding a `--stylename` to the element class.

You can however, go a step further by adding a template for an element:
```
templates/Syntro/BootstrapElemental/Blocks/ClassName__stylename.ss
```

and completely change how an element is rendered.

## Template variables

Inside an element template, you can use the following variables (apart from the
ones provided by elemental):

* `$TextColor`
* `$TextColorClass`
* `$BackgroundColor`
* `$BackgroundColorClass`
* `$LinkColor`
* `$BGImage`
