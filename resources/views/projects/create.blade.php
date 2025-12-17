@extends('layouts.app')

@section('title', 'Submit Project')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div class="bg-white py-8 px-6 shadow rounded-lg sm:px-10">
            <h2 class="mb-6 text-center text-3xl font-extrabold text-pink-500">Submit Project</h2>
            <form class="space-y-6" action="{{ route('projects.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Judul Project</label>
                    <input id="title" name="title" type="text" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500">
                </div>
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                    <textarea id="description" name="description" rows="3" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500"></textarea>
                </div>
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700">Tipe Project</label>
                    <input id="type" name="type" type="text" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500" placeholder="Contoh: Website, Video, dsb">
                </div>
                <div>
                    <label for="url" class="block text-sm font-medium text-gray-700">URL Project</label>
                    <input id="url" name="url" type="url" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500" placeholder="https://...">
                </div>
                <div>
                    <label for="thumbnail" class="block text-sm font-medium text-gray-700">Thumbnail (opsional)</label>
                    <input id="thumbnail" name="thumbnail" type="file" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-pink-50 file:text-pink-700 hover:file:bg-pink-100">
                </div>
                <div>
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium bg-pink-500 text-white hover:bg-pink-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500 transition">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 