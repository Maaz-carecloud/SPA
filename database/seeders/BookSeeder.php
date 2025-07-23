<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $books = [
            [
                'name' => 'Mathematics Grade 9',
                'subject_code' => 'MATH-09',
                'author' => 'Dr. Ahmed Khan',
                'price' => 450,
                'quantity' => 50,
                'due_quantity' => 15,
                'rack' => 'A-1-001',
            ],
            [
                'name' => 'English Literature',
                'subject_code' => 'ENG-LIT',
                'author' => 'Sarah Johnson',
                'price' => 380,
                'quantity' => 40,
                'due_quantity' => 8,
                'rack' => 'B-2-015',
            ],
            [
                'name' => 'Physics Fundamentals',
                'subject_code' => 'PHY-101',
                'author' => 'Prof. Muhammad Ali',
                'price' => 520,
                'quantity' => 35,
                'due_quantity' => 12,
                'rack' => 'C-1-023',
            ],
            [
                'name' => 'Chemistry Basics',
                'subject_code' => 'CHEM-101',
                'author' => 'Dr. Fatima Shah',
                'price' => 480,
                'quantity' => 45,
                'due_quantity' => 20,
                'rack' => 'C-2-045',
            ],
            [
                'name' => 'Biology Grade 10',
                'subject_code' => 'BIO-10',
                'author' => 'Dr. Hassan Malik',
                'price' => 420,
                'quantity' => 38,
                'due_quantity' => 5,
                'rack' => 'D-1-012',
            ],
            [
                'name' => 'Computer Science Principles',
                'subject_code' => 'CS-101',
                'author' => 'Engr. Ayesha Tariq',
                'price' => 650,
                'quantity' => 30,
                'due_quantity' => 18,
                'rack' => 'E-3-067',
            ],
            [
                'name' => 'Pakistan Studies',
                'subject_code' => 'PAK-STD',
                'author' => 'Prof. Tariq Hussain',
                'price' => 320,
                'quantity' => 55,
                'due_quantity' => 25,
                'rack' => 'F-1-089',
            ],
            [
                'name' => 'Islamic Studies',
                'subject_code' => 'ISL-STD',
                'author' => 'Maulana Abdul Rahman',
                'price' => 280,
                'quantity' => 60,
                'due_quantity' => 10,
                'rack' => 'G-2-034',
            ],
            [
                'name' => 'Urdu Literature',
                'subject_code' => 'URD-LIT',
                'author' => 'Dr. Zahra Batool',
                'price' => 350,
                'quantity' => 42,
                'due_quantity' => 16,
                'rack' => 'H-1-078',
            ],
            [
                'name' => 'Geography World Atlas',
                'subject_code' => 'GEO-ATL',
                'author' => 'Prof. Nasir Ahmed',
                'price' => 580,
                'quantity' => 25,
                'due_quantity' => 7,
                'rack' => 'I-2-056',
            ],
            [
                'name' => 'Statistics and Probability',
                'subject_code' => 'STAT-101',
                'author' => 'Dr. Sana Riaz',
                'price' => 470,
                'quantity' => 32,
                'due_quantity' => 14,
                'rack' => 'A-3-125',
            ],
            [
                'name' => 'Economics Principles',
                'subject_code' => 'ECO-101',
                'author' => 'Prof. Imran Sheikh',
                'price' => 390,
                'quantity' => 28,
                'due_quantity' => 9,
                'rack' => 'J-1-043',
            ],
            [
                'name' => 'Art and Design',
                'subject_code' => 'ART-101',
                'author' => 'Ms. Rubina Khan',
                'price' => 300,
                'quantity' => 20,
                'due_quantity' => 3,
                'rack' => 'K-2-067',
            ],
            [
                'name' => 'Physical Education',
                'subject_code' => 'PE-101',
                'author' => 'Coach Ahmed Ali',
                'price' => 250,
                'quantity' => 35,
                'due_quantity' => 11,
                'rack' => 'L-1-089',
            ],
            [
                'name' => 'Advanced Mathematics',
                'subject_code' => 'MATH-ADV',
                'author' => 'Dr. Kashif Mahmood',
                'price' => 720,
                'quantity' => 22,
                'due_quantity' => 6,
                'rack' => 'A-2-156',
            ],
        ];

        foreach ($books as $book) {
            Book::create($book);
        }
    }
}
