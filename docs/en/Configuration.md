# Configuration

The bootstrap base element allows you to apply a wide range of configuration
in order to make elements suitable for bootstrap design. By default, the
bootstrap blocks are intended to work in a full-width layout or a container with
no padding.

## Background colors
Sections can have a background color. Depending on the kind of section and
custom styling, it may be necessary to limit or add the available options.

### Setting allowed background colors
By default, a `default` option will always appear, resulting
in no color class being applied (falling back to the global color).

you can add any further color by adding the following to your config:

```yaml
YourSectionClass:
    background_colors:
        primary: The primary blue color
        grad-primary: A gradient using the primary blue color # This is a custom color. you have to define '.bg-grad-primary' yourself
```

### Setting the default text color
The label `default` is reserved for the default option. setting it as mentioned
above will lead to it being overwritten. You can however set a default color name
to be used for when the default label is selected (in order to account for sections
with a dark background by default):

```yaml
YourSectionClass:
    default_background_color: grad-dark
```



## Background image
By default, an editor may choose to use a background image for a specific
section. In this case, the editor may also choose a text color, as we cannot
evaluate the color of the image and find a fitting text color.

### Enabling / disabling background image
If you want to disable background images for specific blocks, you can do so
by setting:

```yaml
YourSectionClass:
    allow_image_background: false
```

### Setting allowed text colors
By default, we add the text color `white`, in order to have image backgrounds
work out of the box. Also, a `default` option will always appear, resulting
in no color class being applied (falling back to the global color).

you can add any further color by adding the following to your config:

```yaml
YourSectionClass:
    text_colors:
        primary: The primary blue color
```

which will present the user with a new option.

> Keep in mind that removing a value from this list will fall back to default.


### Setting the default text color
The label `default` is reserved for the default option. setting it as mentioned
above will lead to it being overwritten. You can however set a default color name
to be used for when the default label is selected (in order to account for sections
with a dark background by default):

```yaml
YourSectionClass:
    default_text_color: light
```

This will then lead to a `text-light` class being rendered for `$TextColorClass`.
