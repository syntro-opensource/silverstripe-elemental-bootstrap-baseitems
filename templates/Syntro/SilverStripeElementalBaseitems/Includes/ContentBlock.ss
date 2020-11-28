<% if ShowTitle || Content || $HTML %>
    <div class="{$ElementName}__contentholder">
        <% if ShowTitle %>
            <h2 class="{$ElementName}__title mb-4">$Title</h2>
        <% end_if %>
        <p class="{$ElementName}__content mb-3">
            <% if $HTML %>
                $HTML
            <% else %>
                $Content
            <% end_if %>
        </p>
    </div>
<% end_if %>
