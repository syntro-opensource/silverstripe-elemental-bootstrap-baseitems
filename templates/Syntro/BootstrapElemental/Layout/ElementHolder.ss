<section class="bg-$ComputedBackgroundColor text-$ComputedTextColor<% if $ComputedBackgroundImage %> background-image<% end_if %>"<% if $ComputedBackgroundImage %> style="background: url('{$ComputedBackgroundImage.URL}') center no-repeat; background-size: cover;"<% end_if %>>
<div class="element $SimpleClassName.LowerCase<% if $StyleVariant %> $StyleVariant<% end_if %><% if $ExtraClass %> $ExtraClass<% end_if %>" id="$Anchor">
        $Element
    </div>
</section>
