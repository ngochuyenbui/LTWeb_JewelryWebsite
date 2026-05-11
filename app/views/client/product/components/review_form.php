<div>
    <label class="block text-sm font-medium text-slate-700 mb-2">Đánh giá (Sao)</label>
    <div class="flex flex-row-reverse justify-end gap-1 star-rating">
        <input type="radio" id="star5" name="rating" value="5" class="hidden" checked />
        <label for="star5" class="cursor-pointer text-slate-300 transition-colors"><svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg></label>
        <input type="radio" id="star4" name="rating" value="4" class="hidden" />
        <label for="star4" class="cursor-pointer text-slate-300 transition-colors"><svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg></label>
        <input type="radio" id="star3" name="rating" value="3" class="hidden" />
        <label for="star3" class="cursor-pointer text-slate-300 transition-colors"><svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg></label>
        <input type="radio" id="star2" name="rating" value="2" class="hidden" />
        <label for="star2" class="cursor-pointer text-slate-300 transition-colors"><svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg></label>
        <input type="radio" id="star1" name="rating" value="1" class="hidden" />
        <label for="star1" class="cursor-pointer text-slate-300 transition-colors"><svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg></label>
    </div>
</div>
<div>
    <label for="content" class="block text-sm font-medium text-slate-700 mb-2">Nội dung bình luận</label>
    <textarea id="content" name="content" rows="4" required class="w-full border border-slate-300 p-3 text-sm focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500" placeholder="Sản phẩm này thế nào?"></textarea>
</div>
<button type="submit" class="w-full bg-slate-900 text-white font-medium py-3 hover:bg-amber-600 transition-colors mt-2">Gửi đánh giá</button>