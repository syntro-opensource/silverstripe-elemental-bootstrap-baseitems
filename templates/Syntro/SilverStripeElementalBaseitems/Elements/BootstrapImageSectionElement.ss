<div class="text-center">
    <% if $ShowTitle %>
        <h2>$Title</h2>
    <% end_if %>
    <% if Image %>
        <img src="$Image.FitMax(2000,2000).URL" alt="$Title" class="w-75 m-auto rounded shadow img-fluid">
    <% end_if %>
    <% if Caption %>
    <p class="text-muted pb-0 pt-3"><i>$Caption</i></p>
    <% end_if %>
</div>
