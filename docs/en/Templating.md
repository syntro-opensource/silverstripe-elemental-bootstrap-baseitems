# Templating

These Items are built to be dropped into any full width bootstrap design and
use only bootstrap classes for styling. This helps with achieving a custom
feel without having to redesign every element. You are however completely free
to implement your own templates.


## Template location
If you want to change how an element renders or create your own extension of
`BootstrapBaseElement`, you can simply create a new template or overwrite
a default template by creating one under
```
templates/Syntro/BootstrapElemental/Blocks/ClassName.ss
```

If you instead want to overwrite the section part, create a template under
```
templates/Syntro/BootstrapElemental/Layout/ElementHolder.ss
```

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
