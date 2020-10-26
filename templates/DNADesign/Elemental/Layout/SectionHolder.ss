<section class="$ElementName py-0 py-sm-2 py-md-4 py-lg-5 <% if $StyleVariant %> $ElementName--$StyleVariant $StyleVariant<% end_if %> $BackgroundColorClass $TextColorClass<% if $BGImage %> background-image<% end_if %>"<% if $BGImage %> style="background: url('{$BGImage.URL}') center no-repeat; background-size: cover;"<% end_if %>>
    <div class="container py-5 element $SimpleClassName.LowerCase<% if $ExtraClass %> $ExtraClass<% end_if %>" id="$Anchor">
        $Element
    </div>
</section>
