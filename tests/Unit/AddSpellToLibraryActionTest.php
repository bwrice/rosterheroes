<?php

namespace Tests\Unit;

use App\Domain\Actions\AddSpellToLibraryAction;
use App\Domain\Models\Spell;
use App\Domain\Models\Squad;
use App\Exceptions\AddSpellException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddSpellToLibraryActionTest extends TestCase
{
    use DatabaseTransactions;

    /** @var Squad */
    protected $squad;

    /** @var Spell */
    protected $spell;

    /** @var AddSpellToLibraryAction */
    protected $domainAction;

    public function setUp(): void
    {
        parent::setUp();
        $this->squad = factory(Squad::class)->create();
        $this->spell = factory(Spell::class)->create();
        $this->domainAction = app(AddSpellToLibraryAction::class);
    }


    /**
     * @test
     */
    public function it_will_throw_an_exception_if_the_squad_owns_the_spell_already()
    {
        $this->squad->spells()->save($this->spell);
        try {
            $this->domainAction->execute($this->squad->fresh(), $this->spell->fresh());
        } catch (AddSpellException $exception) {
            $this->assertEquals(AddSpellException::CODE_ALREADY_OWNS, $exception->getCode());
        }
    }

    /**
     * @test
     */
    public function it_will_add_a_spell_to_a_squad()
    {
        $this->domainAction->execute($this->squad, $this->spell);

        $squad = $this->squad->fresh();
        $spell = $squad->spells()->where('id', '=', $this->spell->id)->first();
        $this->assertNotNull($spell);
    }
}
