<?php
// $topPerformers — array of performers, $accentColor — 'indigo' or 'violet'
$accentColor = $accentColor ?? 'indigo';
$colors = [
    'indigo' => ['ring' => 'ring-indigo-400', 'bg' => 'bg-indigo-600', 'badge' => 'bg-indigo-100 text-indigo-700', 'bar' => 'bg-indigo-500', 'track' => 'bg-indigo-100'],
    'violet' => ['ring' => 'ring-violet-400', 'bg' => 'bg-violet-600', 'badge' => 'bg-violet-100 text-violet-700', 'bar' => 'bg-violet-500', 'track' => 'bg-violet-100'],
];
$c = $colors[$accentColor];

$medals = ['🥇', '🥈', '🥉'];
$maxHours = !empty($topPerformers) ? max(array_column($topPerformers, 'totalHours')) : 1;
?>

<div class="bg-white rounded-xl border border-gray-100 p-5">
    <div class="flex items-center justify-between mb-5">
        <div>
            <h3 class="text-sm font-semibold text-gray-800">🏆 Top Performers</h3>
            <p class="text-xs text-gray-400 mt-0.5"><?= date('F Y') ?> · Based on hours logged</p>
        </div>
        <?php if (!empty($topPerformers)): ?>
        <span class="text-xs px-2.5 py-1 <?= $c['badge'] ?> rounded-full font-medium">This Month</span>
        <?php endif; ?>
    </div>

    <?php if (empty($topPerformers)): ?>
    <div class="py-8 text-center text-sm text-gray-400">
        No attendance data yet this month
    </div>
    <?php else: ?>
    <div class="space-y-4">
        <?php foreach ($topPerformers as $i => $p): ?>
        <?php $pct = $maxHours > 0 ? ($p['totalHours'] / $maxHours) * 100 : 0; ?>
        <div class="flex items-center gap-3">
            <!-- Rank + Avatar -->
            <div class="relative flex-shrink-0">
                <img src="<?= htmlspecialchars($p['profileImage'] ?: BASE_URL . '/public/images/avatar.png') ?>"
                     class="w-10 h-10 rounded-full object-cover border-2 <?= $i === 0 ? $c['ring'] . ' ring-2' : 'border-gray-200' ?>"
                     alt="<?= htmlspecialchars($p['name']) ?>">
                <?php if ($i < 3): ?>
                <span class="absolute -top-1.5 -right-1.5 text-sm leading-none"><?= $medals[$i] ?></span>
                <?php else: ?>
                <span class="absolute -top-1 -right-1 w-4 h-4 <?= $c['bg'] ?> text-white text-[9px] font-bold rounded-full flex items-center justify-center"><?= $i + 1 ?></span>
                <?php endif; ?>
            </div>

            <!-- Info + Bar -->
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between mb-1">
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-gray-800 truncate"><?= htmlspecialchars($p['name']) ?></p>
                        <p class="text-xs text-gray-400 truncate capitalize"><?= htmlspecialchars($p['position']) ?></p>
                    </div>
                    <div class="text-right flex-shrink-0 ml-2">
                        <p class="text-sm font-bold text-gray-700"><?= $p['totalHours'] ?>h</p>
                        <p class="text-xs text-gray-400"><?= $p['totalDays'] ?> days</p>
                    </div>
                </div>
                <div class="h-1.5 <?= $c['track'] ?> rounded-full overflow-hidden">
                    <div class="h-full <?= $c['bar'] ?> rounded-full transition-all duration-500"
                         style="width: <?= round($pct) ?>%"></div>
                </div>
                <?php if ($p['lateCount'] > 0): ?>
                <p class="text-xs text-amber-500 mt-0.5"><?= $p['lateCount'] ?> late arrival<?= $p['lateCount'] > 1 ? 's' : '' ?></p>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>
