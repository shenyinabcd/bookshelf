
<div class="main">

<?php include "template/search_bar.php"; ?>

	<div class="mid_content">
		<h3>我的书架</h3>
		<div class="content_box">
			<div class="box_demo my_book">
				<table>
					<tbody>
						<tr>
							<th>当前所拥有书籍</th>
							<th>当前状态</th>
							<th>归属权</th>
							<th></th>
						</tr>
						<?php foreach ($books as $book): ?>
						<tr>
							<td><?php echo $book['name'];?></td>
							<td>
								<?php if($book['book_status']==0) echo "闲置中"; 
										elseif ($book['book_status']==1) echo "预约中";
										else echo "借出";	
								?>
							</td>
							<td><?php if($book['book_right']) echo "共同";else echo "私有"; ?></td>
							<td class="remove">
								<?php if($book['book_status']==0&&$book['book_right']==0):?>
								<form action="<?php echo site_url('home/my_book'); ?>" method="POST">
									<input type="hidden" name="book_id" value="<?php echo $book['id'];?>" />
									<input type="submit" value="下架" />
								</form>		
								<?php endif;?>
							</td>
							
						</tr>
						<?php endforeach ?>						
						<!--
						<tr>
							<td>毛邓三的那些事</td>
							<td>借出</td>
							<td>共同</td>
							<td></td>
						</tr>
						<tr>
							<td>xxx的xxx</td>
							<td>预约中</td>
							<td>个人</td>
							<td></td>
						</tr>
						<tr>
							<td>xxx的xxx</td>
							<td>闲置中</td>
							<td>个人</td>
							<td class="remove"><a href="#">下架</a></td>
						</tr>
						<tr>
							<td>xxx的xxx</td>
							<td>闲置中</td>
							<td>个人</td>
							<td class="remove"><a href="#">下架</a></td>
						</tr>
						-->
					</tbody>
				</table>
				<a href="<?php echo site_url('home');?>" class="back_up">返回</a>

				<?php echo $this->pagination->create_links();?><!-- 输出分页模块 -->

			</div>
			<div class="bottom_shadow"></div><!-- 块级区域下方的底层阴影 -->
		</div><!-- 包裹整块质感效果的div -->
	</div>
</div><!--end of main-->
