<section class="$ElementName <% if $StyleVariant %> $ElementName--$StyleVariant $StyleVariant<% end_if %> bg-$ComputedBackgroundColor text-$ComputedTextColor<% if $ComputedBackgroundImage %> background-image<% end_if %>"<% if $ComputedBackgroundImage %> style="background: url('{$ComputedBackgroundImage.URL}') center no-repeat; background-size: cover;"<% end_if %>>
<div class="element $SimpleClassName.LowerCase<% if $ExtraClass %> $ExtraClass<% end_if %>" id="$Anchor">
        $Element
    </div>
</section>
