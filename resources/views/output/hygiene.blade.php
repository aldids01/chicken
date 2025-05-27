<style>

    .logbook-container {
        width: 100%;
        margin:0 auto;
        background-color: #fff;
        padding: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
    }

    header {
        text-align: center;
        margin-bottom: 30px;
        border-bottom: 2px solid #eee;
        padding-bottom: 20px;
    }

    .company-name {
        font-size: 1.5em;
        color: #2c3e50;
        margin-bottom: 5px;
        line-height: 1.0;
    }

    header h2 {
        font-size: 1.5em;
        color: #34495e;
        margin-top: 0;
        text-transform: uppercase;
    }


    .header-details {
        text-align: left;
        margin-top: 20px;
        font-size: 1.1em;
    }

    .header-details p {
        margin: 5px 0;
    }

    .header-details span {
        border-bottom: 1px dashed #999;
        padding-bottom: 2px;
        display: inline-block;
        min-width: 150px;
    }

    .logbook-table-section {
        margin-bottom: 30px;
        overflow-x: auto; /* Enables horizontal scrolling for tables */
    }

    .print-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 0;
        font-size: 0.9em;
    }

    .print-table, .tb-hd, .tdd {
        border: 1px solid #000000;
    }

    .tb-hd, .tdd {
        padding: 5px 10px;
        text-align: left;
        white-space: wrap; /* Prevents text wrapping in table cells */
    }

    .tb-hd {
        background-color: #f2f2f2;
        font-weight: bold;
        color: #555;
        text-transform: capitalize;
    }

    tbody tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    tbody tr:hover {
        background-color: #f1f1f1;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .logbook-container {
            padding: 15px;
        }

        .company-name {
            font-size: 2em;
        }

        header h2 {
            font-size: 1.5em;
        }

        .tb-hd, .tdd {
            padding: 8px 10px;
            font-size: 0.85em;
        }
    }

    @media (max-width: 480px) {
        .company-name {
            font-size: 1.8em;
        }

        header h2 {
            font-size: 1.2em;
        }

        .header-details {
            font-size: 0.9em;
        }

        .tb-hd, .tdd {
            padding: 6px 8px;
            font-size: 0.8em;
        }
    }

    @media print {
        .logbook-container {
            width: 100%; /* Ensure it takes full width of printable area */
            margin: 0; /* Remove any potential margins */
            box-shadow: none; /* Remove shadows for print */
            border-radius: 0; /* Remove border-radius for print */
        }

        .print-table {
            font-size: 0.8em; /* Slightly reduce font size for print */
        }

        .tb-hd, .tdd {
            padding: 3px 5px; /* Reduce padding for print */
        }

        /* You might want to remove headers/footers that are not relevant to print */
        header {
            margin-bottom: 10px; /* Reduce header margin if needed */
            border-bottom: 1px solid #eee; /* Lighter border for print */
            padding-bottom: 10px;
        }
    }

</style>
<div class="logbook-container">
    <header>
        <h1 class="company-name">YSG <br> CHICKEN CO</h1>
        <h2>HYGIENE AND CLEANLINESS CHECKLIST</h2>
        <div class="header-details">
            <p><strong>Date:</strong> <span>{{$record->created_at}}</span></p>
            <p><strong>Supervisor on Duty:</strong> <span>{{$record->supervisor->name}}</span></p>
        </div>
    </header>
    <div class="logbook-table-section">
        <table class="print-table">
            <thead>
            <tr>
                <th class="tb-hd">SN</th>
                <th class="tb-hd">Area</th>
                <th class="tb-hd">Status</th>
                <th class="tb-hd">Remarks</th>
            </tr>
            </thead>
            <tbody>
            @foreach($record->hygiene as $item)
            <tr>
                <td class="tdd">{{ $loop->iteration }}</td>
                <td class="tdd">{{ $item->area->name }}</td>
                <td class="tdd">{{ $item->status }}</td>
                <td class="tdd">{{ $item->remark }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
