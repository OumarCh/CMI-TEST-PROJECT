<?php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use App\Entity\Comment;

class AppExtension  extends AbstractExtension 
{
    public function getFunctions()
    {
        return [
            new TwigFunction('note', [$this, 'calculTotalNote']),
        ];
    }

    public function calculTotalNote(Comment $comment)
    {
        $sumNotes = array_sum(array_map(function($n) {
            return $n->getNote();
        }, $comment->getNotes()->toArray()));

        $countNotes = count($comment->getNotes()->toArray());

        return $countNotes > 0 ? floor($sumNotes/$countNotes).'/5' : 'aucune';
    }
}