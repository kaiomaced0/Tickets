@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-border bg-card text-foreground focus:border-primary focus:ring-primary rounded-md shadow-sm placeholder:text-muted-foreground']) }}>
