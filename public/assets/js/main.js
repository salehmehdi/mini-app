$(function()
{
// --- Registration form AJAX ---
    $('#regForm').on('submit', function(e) 
    {
        e.preventDefault();
        
        $('.invalid-feedback').text('').hide();
        $('#formMessage').html('');
        $('input').removeClass('is-invalid');
        
        $.ajax({
            url: 'src/register.php',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(res) 
            {
      
                $('#formMessage').html('<div class="alert alert-success">' + res.message + '</div>');
                $('#regForm')[0].reset();
               
            },
            error: function(xhr) 
            {                
                try 
                {
                    let res = JSON.parse(xhr.responseText);
                    
                    if (res.status === 'error') 
                    {
                        if (res.message) 
                        {
                            $('#formMessage').html('<div class="alert alert-danger">' + res.message + '</div>');
                        }
                        
                        if (res.fields) 
                        {
                            for (let fieldName in res.fields) 
                            {
                                $('#err_' + fieldName).text(res.fields[fieldName]).show();
                                $('input[name="' + fieldName + '"]').addClass('is-invalid');
                            }
                        }
                    } 
                    else 
                    {
                        $('#formMessage').html('<div class="alert alert-danger">bilinmedik bir sehf.</div>');
                    }
                } 
                catch (e) 
                {
                    $('#formMessage').html('<div class="alert alert-danger">Bilinmedik bir sehf.</div>');
                }
            }
        });
    });

    // --- DataTable + Export ---
    if($('#registrations').length)
    {
        var table = $('#registrations').DataTable({
            processing: true,
            serverSide: true,
            ajax: 'api_registrations.php',
            columns: [
                { data: 'id' },
                { data: 'full_name' },
                { data: 'email' },
                { data: 'company' },
                { data: 'created_at' }
            ]
        });

        $('#exportXLSX').on('click', function(e)
        {
            e.preventDefault();
            var search   = table.search();
            var order    = table.order()[0]; 
            var orderBy  = ['id','full_name','email','company','created_at'][order[0]];
            var orderDir = order[1];
            window.location.href = 'export_xlsx.php?search=' + encodeURIComponent(search) + '&orderBy=' + orderBy + '&orderDir=' + orderDir;
        });

        $('#exportPDF').on('click', function(e)
        {
            e.preventDefault();
            var search   = table.search();
            var order    = table.order()[0];
            var orderBy  = ['id','full_name','email','company','created_at'][order[0]];
            var orderDir = order[1];
            window.location.href = 'export_pdf.php?search=' + encodeURIComponent(search) + '&orderBy=' + orderBy + '&orderDir=' + orderDir;
        });
    }
});
