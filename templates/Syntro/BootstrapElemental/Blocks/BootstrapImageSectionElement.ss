<div class="py-5 container text-center">
    <% if $ShowTitle %>
        <h2>$Title</h2>
    <% end_if %>
    <% if Image %>
        <img src="$Image.FitMax(2000,2000).URL" alt="$Title" class="img-fluid">
    <% end_if %>
</div>
