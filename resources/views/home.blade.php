<x-layout-app pageTitle="Home">

    <div class='w-100 p-4'>

        <h3>Home</h3>

        <hr>
        <div class='d-flex'>
            <x-info-title-value itemTitle="Total colaborators" :itemValue="$data['total_colaborators']" />
            <x-info-title-value itemTitle="Total deleted colaborators" :itemValue="$data['total_colaborators_deleted']" />
            <x-info-title-value itemTitle="Total salary" :itemValue="$data['total_salary']" />
        </div>

        <div class='d-flex'>
            <x-info-title-collection itemTitle="Colaborators by department" :collection="$data['total_colaborators_per_department']" />
            <x-info-title-collection itemTitle="Total salary by department" :collection="$data['total_salary_by_department']" />
        </div>
    </div>
    
</x-layout-app>