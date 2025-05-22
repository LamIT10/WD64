@section('content')
<div class="w-full bg-[#F4F4F4] p-4 font-sans min-h-screen">

  <!-- Dyeing Form Accordion Item 1 -->
  <div class="bg-[#F8F8F8] rounded-[10px] p-4 mb-4">
    <button onclick="toggleContent('form1')" class="w-full flex justify-between items-center text-[15px] font-semibold">
      Dyeing Form
      <i class="fa-solid fa-chevron-down text-gray-400"></i>
    </button>
    <div id="form1" class="mt-3 text-[14px] text-black space-y-2">
      <ul class="list-disc pl-5">
        <li>First Lot: Color Not Matched</li>
        <li>First Lot: Color Not Matched</li>
        <li>First Lot: Color Not Matched</li>
        <li>First Lot: Color Not Matched</li>
      </ul>
    </div>
  </div>

  <!-- Dyeing Form Accordion Item 2 -->
  <div class="bg-[#F8F8F8] rounded-[10px] p-4 mb-4">
    <button onclick="toggleContent('form2')" class="w-full flex justify-between items-center text-[15px] font-semibold">
      Dyeing Form
      <i class="fa-solid fa-chevron-down text-gray-400"></i>
    </button>
    <div id="form2" class="mt-3 text-[14px] text-black space-y-2 hidden">
      <ul class="list-disc pl-5">
        <li>First Lot: Color Not Matched</li>
        <li>First Lot: Color Not Matched</li>
        <li>First Lot: Color Not Matched</li>
        <li>First Lot: Color Not Matched</li>
      </ul>
    </div>
  </div>

</div>

<!-- Script toggle -->
<script>
  function toggleContent(id) {
    const content = document.getElementById(id);
    content.classList.toggle('hidden');
  }
</script>

@endsection