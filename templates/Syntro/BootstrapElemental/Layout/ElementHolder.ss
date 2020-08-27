<section class="$ElementName <% if $StyleVariant %> $ElementName--$StyleVariant $StyleVariant<% end_if %> $BackgroundColorClass $TextColorClass<% if $BackgroundImage %> background-image<% end_if %>"<% if $BackgroundImage %> style="background: url('{$BackgroundImage.URL}') center no-repeat; background-size: cover;"<% end_if %>>
<div class="element $SimpleClassName.LowerCase<% if $ExtraClass %> $ExtraClass<% end_if %>" id="$Anchor">
        $Element
    </div>
</section>
