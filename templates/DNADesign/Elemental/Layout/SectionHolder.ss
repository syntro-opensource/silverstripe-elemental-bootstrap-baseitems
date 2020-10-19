<section class="$ElementName <% if $StyleVariant %> $ElementName--$StyleVariant $StyleVariant<% end_if %> $BackgroundColorClass $TextColorClass<% if $BGImage %> background-image<% end_if %>"<% if $BGImage %> style="background: url('{$BGImage.URL}') center no-repeat; background-size: cover;"<% end_if %>>
<div class="element $SimpleClassName.LowerCase<% if $ExtraClass %> $ExtraClass<% end_if %>" id="$Anchor">
        $Element
    </div>
</section>
