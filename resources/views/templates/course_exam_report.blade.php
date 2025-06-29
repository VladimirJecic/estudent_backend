<table>
    <thead>
        <tr style="background-color: #e0f0ff;">
            <th colspan="3" style="font-weight: bold; text-align: center;">
                Izveštaj za ispit {{ $reportDTO->courseExamName }} u roku {{ $reportDTO->examPeriodName }}
            </th>
        </tr>
        <tr style="background-color: #d9ead3; font-weight: bold; border: 1px solid #000;">
            <th style="border: 1px solid #000; width: 25%;">Broj indeksa</th>
            <th style="border: 1px solid #000; width: 40%;">Ime</th>
            <th style="border: 1px solid #000; width: 15%;">Ocena</th>
        </tr>
    </thead>
    <tbody>
        @foreach($reportDTO->reportItemList as $reportItemDTO)
            <tr>
                <td style="border: 1px solid #000;">{{ $reportItemDTO->studentIndexNum }}</td>
                <td style="border: 1px solid #000;">{{ $reportItemDTO->studentName }}</td>
                <td style="border: 1px solid #000; text-align: center;">{{ $reportItemDTO->mark }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr style="background-color: #f9f9f9; font-weight: bold;">
            <td style="border: 1px solid #000;"></td>
            <td style="border: 1px solid #000;">Prosečna ocena: {{ $reportDTO->averageScore }}</td>
            <td style="border: 1px solid #000;">Izlaznost: {{ $reportDTO->attendancePercentage }}%</td>
        </tr>
    </tfoot>
</table>
